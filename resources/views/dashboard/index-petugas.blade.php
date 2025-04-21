@extends('layouts.app')
@section('content')
    <div class="rounded-xl p-5 border border-black/10">
        <h1 class="text-2xl font-semibold">Selamat datang, Petugas!</h1>
        <div class="rounded-xl border border-black/10 flex flex-col gap-4 mt-7">
            <span class="h-14 bg-gray-200 flex justify-center items-center w-full rounded-t-xl text-black/50">Total Penjualan
                Hari ini</span>
            <div class="flex flex-col items-center gap-2">
                <span class="text-xl">
                    {{ $totalSalesToday }}
                </span>
                <span class="text-black/50">Jumlah total penjualan yang terjadi hari ini.</span>
            </div>
            <span class="h-14 bg-gray-200 flex justify-center items-center rounded-b-xl text-black/50">Terakhir diperbarui:
                {{ now()->format('d M Y H:i') }}</span>
        </div>
    </div>
@endsection
