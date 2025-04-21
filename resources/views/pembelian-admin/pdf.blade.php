<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px 60px;
            font-size: 14px;
            color: #000;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .left-info {
            flex: 1;
        }

        .right-info {
            flex: 1;
            text-align: right;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        h2 {
            font-size: 16px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 10px;
            text-align: right;
            vertical-align: top;
        }

        th {
            background-color: #f3f3f3;
        }

        .table-header {
            text-align: left;
        }

        .strike {
            text-decoration: line-through;
            color: gray;
        }

        .footer {
            text-align: right;
            margin-top: 50px;
            font-size: 14px;
        }

        .bold {
            font-weight: bold;
        }

        .totals-row td {
            padding: 5px 10px;
            font-weight: bold;
            text-align: right;
            border: none;
        }

        .totals-label,
        .totals-value {
            padding: 4px 8px;
            font-weight: bold;
        }

        .totals-value {
            text-align: right;
            font-weight: bold;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="left-info">
            <h1>Indo April</h1>
            <h2>Alamat: <span>Jalan Raya Puncak, Kecamatan Cisarua, Kabupaten Bogor</span></h2>
            <h2>No Telp : <span>0857996392</span></h2>
            <p>
                Tanggal: {{ $sale->created_at->format('d/m/Y') }}<br>
            </p>
        </div>
        <div class="right-info">
            <h1>Invoice - {{ $sale->no_invoice }}</h1>
            @if ($sale->member)
                <div>
                    <div class="flex flex-col gap-1 mt-1">
                        <span><span>PELANGGAN:</span> {{ $sale->member->name }}</span>
                    </div>
                    <div class="flex flex-col gap-1 mt-1">
                        <span><span> No. HP:</span> {{ $sale->member->phone_number }}</span>
                    </div>
                    <div class="flex flex-col gap-1 mt-1">
                        <span><span>Member Sejak:</span> {{ $sale->member->created_at->format('d M Y') }}</span>

                    </div>
                    <div class="flex flex-col gap-1 mt-1">
                        <span><span> Poin:</span> {{ $sale->member->poin }}</span>
                    </div>
                </div>
            @else
                <p>
                    Status: <strong>Bukan Member</strong>
                </p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="table-header">Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td class="table-header">{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>
                        @if (isset($item->product->original_price) && $item->product->original_price > $item->product->price)
                            <span class="strike">Rp.
                                {{ number_format($item->product->original_price, 0, ',', '.') }}</span><br>
                        @endif
                        Rp. {{ number_format($item->product->price, 0, ',', '.') }}
                    </td>
                    <td>Rp. {{ number_format($item->qty * $item->product->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Section -->
    <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: 10px;">
        <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <tr class="totals-row">
                <td style="width: 70%;"></td>
                <td class="totals-label">Total:</td>
                <td class="totals-value">Rp. {{ number_format($sale->total_price, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-row gap-5">
                <td></td>
                <td class="totals-label">Total Bayar:</td>
                <td class="totals-value">Rp. {{ number_format($sale->total_paid, 0, ',', '.') }}</td>
            </tr>
            <tr class="totals-row">
                <td></td>
                <td class="totals-label">Kembalian:</td>
                <td class="totals-value">
                    Rp.
                    {{ number_format($sale->total_paid - $sale->total_price + $sale->total_use_points, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y H:i') }}<br>
        <strong>Terima kasih atas pembelian Anda!</strong>
    </div>
</body>

</html>
