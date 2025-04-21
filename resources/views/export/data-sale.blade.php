<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pelanggan</th>
                <th>No HP</th>
                <th>Poin</th>
                <th>Produk</th>
                <th>Total Harga</th>
                <th>Total Bayar</th>
                <th>Total Diskon Poin</th>
                <th>Total Kembalian</th>
                <th>Tanggal Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->member ? $sale->member->name : 'Non-member' }}</td>
                    <td>{{ $sale->member ? $sale->member->phone : 'N/A' }}</td>
                    <td>{{ $sale->member ? $sale->member->points : 0 }}</td>
                    <td>
                        @foreach ($sale->items as $item)
                            <p>{{ $item->product->name }} ({{ $item->qty }}x)</p>
                        @endforeach
                    </td>
                    <td>{{ 'Rp. ' . number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td>{{ 'Rp. ' . number_format($sale->total_paid, 0, ',', '.') }}</td>
                    <td>{{ 'Rp. ' . number_format($sale->total_discount_points, 0, ',', '.') }}</td>
                    <td>{{ 'Rp. ' . number_format($sale->total_change ?? $sale->total_paid - $sale->total_price, 0, ',', '.') }}
                    </td>
                    <td>{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
