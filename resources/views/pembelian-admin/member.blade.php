@extends('layouts.app')

@section('page_title', 'Penjualan')
@section('page_breadcrumb', 'Penjualan')

@section('content')
    <div class="rounded-xl p-12 border max-w-5xl mx-auto mt-10 bg-transparent">
        <div class="grid grid-cols-2 gap-12">
            <div class="space-y-6 border p-5 rounded-lg">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-500">
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->items as $item)
                            <tr class="border-t">
                                <td class="py-4">{{ $item->product->name }}</td>
                                <td class="py-4">{{ $item->qty }}</td>
                                <td class="py-4">{{ $item->product->price }}</td>
                                <td class="py-4">{{ $item->qty * $item->product->price }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6 space-y-2">
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total Harga</span>
                        <span>{{ $sale->total_price }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total Bayar</span>
                        <span class="text-red-600">{{ $sale->total_paid }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <form action="{{ route('sale.update', $sale->id) }}" method="POST" class=" space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="member_name" class="block text-sm text-gray-600">Nama Member (identitas)</label>
                        <input type="text" id="member_name" class="mt-1 w-full border rounded-md p-2 bg-transparent"
                            name="member_name" {{ !$isNewMember ? 'disabled=""' : '' }} value="{{ $sale->member->name }}"
                            required />
                    </div>
                    <div>
                        <label for="poin" class="block text-sm text-gray-600">Poin</label>
                        <input type="text" id="poin" value="{{ $sale->member->poin }}"
                            class="mt-1 w-full border rounded-md p-2 bg-gray-100" disabled>
                        <div class="mt-2 flex items-center">
                            <input type="checkbox" id="use_poin" class="mr-2" name="use_poin"
                                {{ $isNewMember ? 'disabled=""' : '' }}>
                            <label for="use_poin" class="text-sm text-gray-500">Gunakan poin
                                @if ($isNewMember)
                                    <span class="text-red-500">Poin
                                        tidak dapat digunakan pada pembelanjaan pertama.</span>
                                @endif
                            </label>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn-primary" type="submit">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
