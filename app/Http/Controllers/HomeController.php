<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role == 'admin'){
            return $this->admin();
        }else{
            return $this->employee();
        }
    }

    public function admin()
    {
        $admin = User::where("role", "admin")->count();
        $petugas = User::where("role", "employee")->count();
        
        $now = Carbon::now();
        $month = $now->month;
        $year = $now->year;
        $today = $now->day; // ambil tanggal hari ini
        
        // Ambil data penjualan per hari
        $salesPerDay = DB::table('sale')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_sales')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $daysInMonth = $now->daysInMonth;
        $allDates = collect(range(1, $today))->map(function ($day) use ($year, $month) {
            return Carbon::create($year, $month, $day)->toDateString();
        });

        $groupedSales = $salesPerDay->keyBy('date');

        // Format ke chart.js style
        $labels = [];
        $data = [];

        foreach ($allDates as $date) {
            $labels[] = Carbon::parse($date)->format('d F Y');
            $data[] = $groupedSales[$date]->total_sales ?? 0;
        }
        // dd($data);

        $productSales = DB::table('sale_items')
            ->join('product', 'sale_items.product_id', '=', 'product.id')
            ->select('product.name as product_name', DB::raw('SUM(sale_items.qty) as total_sold'))
            ->groupBy('product.name')
            ->orderByDesc('total_sold')
            ->get();
        
        $pieLabels = $productSales->pluck('product_name');
        $pieData = $productSales->pluck('total_sold');
      
        return view('/dashboard.index-admin', compact('admin', 'petugas', 'labels', 'data', 'pieLabels', 'pieData'));
    }


    public function employee()
    {
        $salesToday = Sale::whereDate('created_at', today())->get();
        $totalSalesToday = $salesToday->count(); 
        return view('/dashboard.index-petugas', compact('totalSalesToday'));
    }
}