<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use  App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\PDF;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter'); // new filter param
    
        $salesQuery = Sale::with("product", "member", "user");
    
        // Filter waktu
        if ($filter === 'day') {
            $salesQuery->whereDate('created_at', now()->toDateString());
        } elseif ($filter === 'week') {
            $salesQuery->whereBetween('created_at', [
                now()->startOfWeek(), now()->endOfWeek()
            ]);
        } elseif ($filter === 'month') {
            $salesQuery->whereMonth('created_at', now()->month);
        } elseif ($filter === 'year') {
            $salesQuery->whereYear('created_at', now()->year);
        }
    
        if ($search) {
            $salesQuery->where(function ($q) use ($search) {
                $q->whereHas('member', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereDate('created_at', $search)
                  ->orWhere('total_price', 'like', "%$search%");
            });
        }
    
        $sales = $salesQuery->paginate(20);
    
        return view('pembelian-admin.index', compact('sales', 'filter'));
    }
    

    public function showDashboard(Request $request)
    {
    
        $salesToday = Sale::whereDate('created_at', now()->timezone('Asia/Jakarta')->toDateString())->get();
        
    
        $totalSalesToday = $salesToday->sum('total_price');
        
        return view('dashboard.index-petugas', compact('totalSalesToday'));
    }

    public function create()
    {
        $products = Product::all();
        return view('pembelian-admin.create', compact('products'));
    }

    public function store(Request $request)
    {
        // dd($request->phone_number);
        $validated = $request->validate([
            'orders' => 'required|json',
            'member_type' => 'required',
            'total_paid' => 'required',
            'total_price' => 'required',
        ]);
    
        $orders = json_decode($request->orders, true);
        $isMember = $validated['member_type'] === "member";
    
        DB::beginTransaction();
    
        try {
            // Check member type sebagai member atau non-member
            $memberId = null;
            if ($isMember) {
                $member = Member::where('phone_number', '=' , $request->phone_number)->first();
                $poin = $this->numericPrice($validated['total_price'])/100;
                if ($member){
                    $memberId = $member->id;
                    Member::where('id', $memberId)->update(['poin' => $member->poin + $poin]);
                } else {
                    $newMember = Member::create([
                        'name' => '',
                        'phone_number' => $request->phone_number,
                        'poin' => $poin,
                    ]);
                    $memberId = $newMember->id;
                }
            }
            // dd($memberId);

            $sale = Sale::create([
                'no_invoice' => $this->generateInvoiceNumber(),
                'member_id' => $memberId,
                'user_id' => auth()->id(),
                'total_price' => $this->numericPrice($validated['total_price']),
                'total_paid' => $this->numericPrice($validated['total_paid']),
            ]);
    
            $saleItems = [];
    
            foreach ($orders as $item) {
                $product = Product::findOrFail($item['id']);
    
                // Check if stock is enough
                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok untuk {$product->name} tidak mencukupi.");
                }
    
                // Decrement stock
                $product->decrement('stock', $item['qty']);
    
                // Prepare item for sale_items
                $saleItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                ];
            }
    
            // Save sale items
            $sale->items()->createMany($saleItems);
    
        DB::commit();

        if ($isMember) {
            return redirect()->route('sale.member', $sale->id)->with('success', 'Berhasil melakukan transaksi!');    
        } else {
            return redirect()->route('sale.detail-print', $sale->id)->with('success', 'Berhasil melakukan transaksi!');
        }
    
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function detailPrint($saleId) 
    {
        try {
            $sale = Sale::with(['items.product', 'member', 'user'])->findOrFail($saleId);
            return view('pembelian-admin.pembayaran', compact('sale'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('sales.index')->with('error', 'Data transaksi tidak ditemukan.');
        }
    }

    private function generateInvoiceNumber(): string
    {
        $lastInvoice = Sale::latest('id')->first();
        $nextNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        return 'INV' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT); // e.g., INV000123
    }

    private function numericPrice($price) {
        return (int) preg_replace('/[^0-9]/', '', $price);
    }

    public function download($id)
    {
        $sale = Sale::with(['items.product', 'member', 'user'])->findOrFail($id);
    
        $pdf = \PDF::loadView('pembelian-admin.pdf', compact('sale'));
    
        return $pdf->download("Invoice-{$sale->no_invoice}.pdf");
    }

    public function downloadExcel(Request $request)
    {
        $filter = $request->input('filter');
    
        return Excel::download(new ProductExport($filter), 'sales_report.xlsx');
    }


    public function member($saleId)
    {
        try {
            $sale = Sale::with(['items.product', 'member', 'user'])->findOrFail($saleId);
            $isNewMember = $sale->member->name === '';
            return view('pembelian-admin.member', compact('sale', 'isNewMember'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('sales.index')->with('error', 'Data transaksi tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $sale = Sale::with(['items.product', 'member', 'user'])->findOrFail($id);
            if ($sale->member->name !== '') {
                if (isset($request->use_poin)) {
                    // Kalkulasi poin ketika jumlh poin melebihi total price
                    $totalUsePoints = 0;
                    $totalMemberPoints = 0;

                    if ($sale->member->poin > $sale->total_price) {
                        $totalMemberPoints = $sale->member->poin - $sale->total_price;
                        $totalUsePoints = $sale->total_price;
                    }else{
                        $totalUsePoints = $sale->member->poin;
                    }
                    Sale::where('id', $sale->id)->update(['total_use_points' => $totalUsePoints]);
                    Member::where('id', $sale->member->id)->update(['poin' => $totalMemberPoints]);
                }
            } else {
                Member::where('id', $sale->member->id)->update(['name' => $request->member_name]);
            }

        DB::commit();
        return redirect()->route('sale.detail-print', $sale->id)->with('success', 'Berhasil melakukan transaksi!');

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function dialog($id)
    {
        try {
            $sale = Sale::with(['items.product', 'member', 'user'])->findOrFail($id);
            if ($sale->member) {
                $data['member'] = [
                    'phone_number'=>$sale->member->phone_number,
                    'poin'=>$sale->member->poin,
                    'join_date'=>$sale->member->created_at->format('d M Y'),
                    'name'=>$sale->member->name,
                ];
            } else {
                $data['member'] = [
                    'phone_number'=> '-',
                    'poin'=>'-',
                    'join_date'=>'-',
                    'name'=>'-',
                ];
            }
           
            $data['items'] = $sale->items;
            $data['member_status'] = $sale->member ? true : false  ;
            $data['user'] = [
                'name'=>$sale->user->name,
            ];
            $data['sale_created'] = $sale->created_at->format('d M Y');
            $data['total_price'] = $sale->total_price;
            return response()->json(['data' => $data]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['data' => null]);
        }

    }
}