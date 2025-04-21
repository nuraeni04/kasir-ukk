@extends('layouts.app')

@section('page_title', 'Produk')
@section('page_breadcrumb', 'Produk')

@section('content')
    <div class="rounded-xl p-5 border">

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex justify-between gap-10">
                <div class="flex flex-col w-full">
                    <label for="name">Nama Produk</label>
                    <input type="name" name="name" class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
                <div class="flex flex-col w-full">
                    <label for="image">Gambar Produk</label>
                    <input type="file" name="image" id="image"
                        class="mt-2 bg-transparent p-2 px-3 border rounded-lg" @error('image') is-invalid @enderror>
                </div>
            </div>
            <div class="flex justify-between gap-10 mt-5 ">
                <div class="flex flex-col w-full">
                    <label for="price">Harga</label>
                    <input type="text" name="price" id="price"
                        class="mt-2 bg-transparent p-2 px-3 w-full border rounded-lg" maxlength="8">
                </div>

                <div class="flex flex-col w-full">
                    <label for="stock">Stok</label>
                    <input type="number" name="stock" class="mt-2 bg-transparent p-2 px-3 border rounded-lg">
                </div>
            </div>
            <div class="flex justify-end ">
                <button class="btn-primary mt-4 flex items-end" type="submit">Simpan</button>
            </div>

        </form>
    </div>
@endsection
