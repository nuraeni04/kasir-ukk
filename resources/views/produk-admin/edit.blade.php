@extends('layouts.app')

@section('page_title', 'Produk')
@section('page_breadcrumb', 'Produk')

@section('content')
    <div class="rounded-xl p-5 border">
        <form action="{{ route('products.update', $products['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="flex justify-between gap-10">
                <div class="flex flex-col w-full">
                    <label for="name">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $products['name']) }}"
                        class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
                <div class="flex flex-col w-full">
                    <label for="image">Gambar Produk</label>
                    <input type="file" name="image"
                        class="mt-2 bg-transparent p-2 px-3 border rounded-lg cursor-pointer">
                    @if ($products['image'])
                        <img src="{{ asset('storage/images/' . $products['image']) }}" alt="Current Image"
                            class="mt-2 w-32 h-32 object-cover">
                    @endif
                </div>
            </div>
            <div class="flex justify-between gap-10 mt-5">
                <div class="flex flex-col w-full">
                    <label for="price">Harga</label>
                    <div class="mt-2 flex items-center border rounded-lg overflow-hidden">
                        <span class="bg-gray-100 text-gray-700 px-3 py-2">Rp</span>
                        <input type="text" name="price"
                            value="{{ old('price', number_format($products['price'], 0, ',', '.')) }}"
                            class="bg-transparent p-2 px-3 w-full outline-none" id="price" oninput="formatHarga(this)">
                    </div>
                </div>
                <div class="flex flex-col w-full">
                    <label for="stock">Stok</label>
                    <input type="number" name="stock" class="mt-2 bg-gray-200 p-2 px-3 border rounded-lg"
                        value="{{ old('stock', $products['stock']) }}" readonly>
                </div>
            </div>
            <div class="flex justify-end">
                <button class="btn-primary mt-4 flex items-end" type="submit">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        // Fungsi untuk format harga
        function formatHarga(input) {
            let angka = input.value.replace(/[^\d]/g, ''); // Menghapus karakter selain angka
            input.value = new Intl.NumberFormat('id-ID').format(angka); // Format sesuai ID
        }
    </script>
@endsection
