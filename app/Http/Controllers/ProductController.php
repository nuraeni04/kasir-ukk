<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $products = Product::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('price', 'like', "%{$search}%")
                  ->orWhere('stock', 'like', "%{$search}%");
            });
        })->paginate(5);
    
        return view('produk-admin.index', compact('products'));
    }
   

    public function create()
    {
        return view('produk-admin.create');
    }

    public function store(Request $request)
    {
        // return $request->file('image')->store('post-image');

        $validateData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|regex:/^\d+$/',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'stock' =>  'required|numeric|min:0|max:10000000000000000000',  
        ]);

        $filename = Str::random(40).".".$request->file('image')->getClientOriginalExtension();

        if($request->file('image')){
            $validateData['image'] = $request->file('image')->storeAs('images', $filename , 'public');
        }

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $filename,
            'stock' => $request->stock, 
        ]);


        return redirect()->route('products.index')->with('success', 'Berhasil');
    }

    public function edit($id)
    {
        $products = Product::find($id);
        return view('produk-admin.edit', compact('products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
        ]);
    
        $product = Product::findOrFail($id);
    
        $cleanPrice = preg_replace('/[^\d]/', '', $request->price);
    
        $productData = [
            'name' => $request->name,
            'price' => $cleanPrice,
            'stock' => $request->stock,
        ];
    
        // Jika user upload gambar baru
        if ($request->hasFile('image')) {
            $filename = Str::random(40) . "." . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('images', $filename, 'public');
            $productData['image'] = $filename;
        }
    
        // Update data produk
        $product->update($productData);
    
        return redirect()->route('products.index')->with('success', 'Berhasil mengupdate data');
    }
    
    public function destroy($id)
    {
        Product::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Berhasil Menghapus Produk');
    }

    public function editStock($id)
    {
        $product = Product::find($id);
        return view('produk-admin.dialog-edit', compact('product'));
    }

    public function updateStock( Request $request, $id)
    {
        {
            $request->validate([
                'name' => 'required',
                'stock' => 'required',
            ]);
    
            $productStock = [
                'name' => $request->name,
                'stock' => $request->stock,
            ];
    
            Product::where('id', $id)->update($productStock);
            return redirect()->route('products.index')->with('success', 'Berhasil menambahkan data');
        }
    }
}