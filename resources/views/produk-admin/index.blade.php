@extends('layouts.app')

@section('page_title', 'Produk')
@section('page_breadcrumb', 'Produk')

@section('content')
    <div class="w-full h-full bg-white p-7 rounded-xl">
        @if (Auth::user()->role == 'admin')
            <div class="flex justify-between">
                <a href="{{ route('products.create') }}" class="btn-primary">
                    Tambah
                </a>
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="flex gap-3">
                        <input type="text" class="p-2 px-3 border rounded-lg" name="search" placeholder="Search.."
                            value="{{ request()->input('search') }}">
                        <input type="submit" class="btn-primary" value="Search">

                        {{-- Tombol Reset --}}
                        <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        @else
            <div class=" flex justify-end">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="flex gap-3">
                        <input type="text" class="p-2 px-3 border rounded-lg" name="search" placeholder="Search.."
                            value="{{ request()->input('search') }}">
                        <input type="submit" class="btn-primary" value="Search">

                        {{-- Tombol Reset --}}
                        <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        @endif

        @if ($products->count() > 0)
            <table class="w-full mt-8 text-left border-separate items-center">
                <thead class="text-gray-600">
                    <tr class="text-black">
                        <th class="border-b-2  border-gray-100 pb-4">No</th>
                        <th colspan="2" class="border-b-2  border-gray-100 pb-4" width="100px">Nama Produk</th>
                        <th class="border-b-2  border-gray-100 pb-4">Harga</th>
                        <th class="border-b-2  border-gray-100 pb-4">Stok</th>
                        @if (Auth::user()->role == 'admin')
                            <th class="border-b-2  border-gray-100 pb-4 w-[250px]">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php($number = 1)
                    @foreach ($products as $value)
                        <tr>
                            <td class="py-4 px-4">{{ $number++ }}</td>
                            <td class="py-4 px-4" width="100px">
                                @if ($value->image)
                                    <img src="{{ asset('storage/images/' . $value->image) }}" alt="produk" height="100"
                                        width="100">
                                @endif
                            </td>
                            <td class="py-4 px-4">{{ $value->name }}</td>
                            <td>{{ 'Rp ' . number_format($value->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-4">{{ $value->stock }}</td>
                            @if (Auth::user()->role == 'admin')
                                <td class="w-[275px]">
                                    <div class="flex items-center justify-center gap-3 ">
                                        <a href="{{ route('products.edit', $value->id) }}" class="btn-warning">Edit</a>

                                        <button
                                            onclick="openDialog({{ $value->id }}, @js($value->name), {{ $value->stock }})"
                                            class="btn-primary">
                                            Update Stok
                                        </button>

                                        @include('produk-admin.dialog-edit')

                                        <form action="{{ route('products.delete', $value->id) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button class="btn-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center mt-4 text-lg text-red-500">Data tidak ditemukan</p> <!-- Menampilkan pesan Not Found -->
        @endif

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
