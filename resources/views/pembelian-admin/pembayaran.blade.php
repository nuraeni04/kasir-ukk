    @extends('layouts.app')

    @section('page_title', 'Penjualan')
    @section('page_breadcrumb', 'Penjualan')

    @section('content')
        <div class="rounded-xl p-12 bg-white">
            <div class="flex gap-4">
                <button class="btn-primary">
                    <a href="{{ route('sale.download', $sale->id) }}">Unduh</a>
                </button>
                <a href="{{ route('sale.index') }}">
                    <button class="btn-secondary">Kembali</button>
                </a>
            </div>
            @if ($sale->member)
                <div class="mt-5">
                    <h2>{{ $sale->member->phone_number }} - {{ $sale->member->name }}</h2>
                    <h2>Member Sejak: <span>{{ $sale->member->created_at->format('d M Y') }} </span></h2>
                    <h2>Member Poin: <span>{{ $sale->member->poin }}</span></h2>
                </div>
            @endif
            <div class="mt-5">
                <h2>Invoice - {{ $sale->no_invoice }}</h2>
                <h2>{{ $sale->created_at->format('d M Y') }} </h2>
            </div>
            <table class="mt-10 bg-gray-200 text-left border p-2 w-full">
                <thead class="">
                    <tr>
                        <th class="py-4 px-6">Produk</th>
                        <th class="py-4 px-6">Harga</th>
                        <th class="py-4 px-6">Quantity</th>
                        <th class="py-4 px-6">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->items as $item)
                        <tr>
                            <td class="py-4 bg-white px-6">{{ $item->product->name }}</td>
                            <td class="py-4 bg-white px-6">
                                {{ 'Rp. ' . number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="py-4 bg-white px-6">{{ $item->qty }}</td>
                            <td class="py-4 bg-white px-6">
                                {{ 'Rp. ' . number_format($item->qty * $item->product->price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        @if ($sale->member)
                            <td class="py-4 px-6 flex flex-col">
                                <span> Poin Digunakan</span>
                                <span>{{ $sale->total_use_points }}</span>
                            </td>
                        @else
                            <td class="py-4 px-6 flex flex-col">
                                &nbsp;
                            </td>
                        @endif
                        <td class="py-4 px-6">
                            <h2>Kasir</h2>
                            <h2 class="text-xl">Petugas</h2>
                        </td>
                        <td class="py-4 px-6 flex flex-col">
                            <div>
                                <h2 class="text-lg font-semibold">Kembalian</h2>
                                <h2 class="text-lg font-semibold">
                                    {{ 'Rp. ' . number_format($sale->total_paid - $sale->total_price + $sale->total_use_points, 0, ',', '.') }}
                                </h2>
                            </div>
                        </td>
                        <td class="py-4 px-6 bg-gray-600">
                            <div>
                                <h2 class="text-lg font-semibold text-left text-gray-300">Total</h2>
                                @if ($sale->total_use_points > 0)
                                    <h2 class="text-2xl font-semibold text-white" style="text-decoration: line-through;">
                                        {{ 'Rp. ' . number_format($sale->total_price, 0, ',', '.') }}
                                    </h2>
                                    <h2 class="text-2xl font-semibold text-white">
                                        {{ 'Rp. ' . number_format($sale->total_price - $sale->total_use_points, 0, ',', '.') }}
                                    </h2>
                                @else
                                    <h2 class="text-2xl font-semibold text-white">
                                        {{ 'Rp. ' . number_format($sale->total_price, 0, ',', '.') }}
                                    </h2>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endsection
