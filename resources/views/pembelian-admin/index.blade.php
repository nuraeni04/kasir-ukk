@extends('layouts.app')

@section('page_title', 'Penjualan')
@section('page_breadcrumb', 'Penjualan')

@section('content')
    <div class="w-full h-full bg-white p-7 rounded-xl">
        @if (Auth::user()->role == 'admin')
            <div class="flex justify-between">
                <div class="flex gap-2">
                    <form action="{{ route('sale.index') }}" method="GET" class="flex gap-3 items-center">
                        <select name="filter_year" onchange="this.form.submit()" class="border rounded-lg p-2">
                            <option value="">Semua Waktu</option>

                            <!-- Filter Waktu -->
                            <option value="day" {{ request('filter_year') == 'day' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ request('filter_year') == 'week' ? 'selected' : '' }}>Minggu Ini
                            </option>
                            <option value="month" {{ request('filter_year') == 'month' ? 'selected' : '' }}>Bulan Ini
                            </option>

                            <!-- Year Filter -->
                            <option value="year" {{ request('filter_year') == 'year' ? 'selected' : '' }}>Pilih Tahun
                            </option>

                            @foreach (range(2020, now()->year) as $year)
                                <option value="{{ $year }}" {{ request('filter_year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>

                        <a href="{{ route('sale.download-excel', ['filter' => request('filter_year')]) }}">
                            <button type="button" class="btn-primary">Export Penjualan (.xlsx)</button>
                        </a>
                    </form>

                </div>
                <form action="{{ route('sale.index') }}" method="GET">
                    <div class="flex gap-3">
                        <input type="search" name="search" placeholder="Search..." class="p-2 px-3 border rounded-lg"
                            value="{{ request('search') }}">
                        <button type="submit" class="btn-primary">Search</button>
                        <a href="{{ route('sale.index') }}"
                            class="btn-secondary p-2 px-4 rounded-lg border text-gray-700 bg-gray-200 hover:bg-gray-300">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        @else
            <div class="flex justify-between items-center">
                <div class="flex gap-2">
                    <form action="{{ route('sale.index') }}" method="GET" class="flex gap-3 items-center">
                        @php
                            $filter = request('filter');
                        @endphp

                        <select name="filter" onchange="this.form.submit()" class="border rounded-lg p-2">
                            <option value="">Semua Waktu</option>
                            <option value="day" {{ $filter === 'day' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="year" {{ $filter === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>

                        <a href="{{ route('sale.download-excel', ['filter' => request('filter')]) }}">
                            <button type="button" class="btn-primary">Export Penjualan (.xlsx)</button>
                        </a>
                        <a href="{{ route('sale.create') }}">
                            <button type="button" class="btn-primary">Tambah</button>
                        </a>
                    </form>

                </div>

                <!-- Kanan: Form Search dan Reset -->
                <form action="{{ route('sale.index') }}" method="GET">
                    <div class="flex gap-3 items-center">
                        {{-- Search --}}
                        <input type="search" name="search" placeholder="Search..." class="p-2 px-3 border rounded-lg"
                            value="{{ request('search') }}">

                        <button type="submit" class="btn-primary">Search</button>
                        <a href="{{ route('sale.index') }}"
                            class="btn-secondary p-2 px-4 rounded-lg border text-gray-700 bg-gray-200 hover:bg-gray-300">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        @endif
        <table class="w-full mt-8 text-left border-separate">
            <thead class="text-gray-600 ">
                <tr class="text-black">
                    <th class="border-b-2  border-gray-100 pb-4">No</th>
                    <th class="border-b-2  border-gray-100 pb-4">Nama Pelanggan</th>
                    <th class="border-b-2  border-gray-100 pb-4">Tanggal Penjualan</th>
                    <th class="border-b-2  border-gray-100 pb-4">Total Harga</th>
                    <th class="border-b-2  border-gray-100 pb-4 w-[170px]">Dibuat Oleh</th>
                    <th class="border-b-2  border-gray-100 pb-4 w-[170px]">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($sales as $key => $sale)
                    <tr>
                        <td class="pt-5">{{ $sales->firstItem() + $key }}</td> {{-- Nomor urut yang nyambung --}}
                        <td class="pt-5">{{ $sale->member ? $sale->member->name : 'NON-MEMBER' }}</td>
                        <td class="pt-5">{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="pt-5">{{ 'Rp. ' . number_format($sale->total_price, 0, ',', '.') }}</td>
                        <td class="pt-5">{{ $sale->user ? $sale->user->name : '-' }}</td>
                        <td class="flex items-center gap-3 w-[200px] pt-5">
                            <button class="btn-warning"
                                onclick="openDialog(
                                '{{ $sale->id }}',
                            )">
                                Lihat
                            </button>
                            <button class="btn-primary">
                                <a href="{{ route('sale.download', $sale->id) }}">Unduh Bukti</a>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('pembelian-admin.dialog')

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
@endsection
