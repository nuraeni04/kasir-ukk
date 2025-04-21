<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil data dari model Sale
     *
     * @return \Illuminate\Support\Collection
     * 
     * 
     */

     protected $filter;

     public function __construct($filter = null)
     {
         $this->filter = $filter;
     }

    public function collection()
    {
        $query = Sale::with('member', 'items.product');

        if ($this->filter === 'day') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'month') {
            $query->whereMonth('created_at', now()->month);
        }

        return $query->get();
    }


    /**
     * Menambahkan headings (header) pada Excel
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }

    /**
     * Mapping data untuk setiap baris di Excel
     *
     * @param  \App\Models\Sale  $sale
     * @return array
     */
    public function map($sale): array
{
    $member = $sale->member;
    $items = $sale->items;
    $productNames = $items->map(function ($item) {
        return $item->product->name;
    })->join(', ');

    $totalDiskonPoin = $member ? $sale->total_use_points : 0;
    $totalKembalian = $sale->total_paid - $sale->total_price;

    return [
        $member ? $member->name : 'Non-member',
        $member ? $member->phone_number : 'N/A',
        $member ? $member->poin : 0,
        $productNames,
        'Rp. ' . number_format($sale->total_price, 0, ',', '.'),
        'Rp. ' . number_format($sale->total_paid, 0, ',', '.'),
        $member ? 'Rp. ' . number_format($totalDiskonPoin, 0, ',', '.') : '-',
        'Rp. ' . number_format($totalKembalian, 0, ',', '.'),
        $sale->created_at->format('Y-m-d H:i:s'),
    ];
}

}
