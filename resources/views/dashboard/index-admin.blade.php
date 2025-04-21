@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="border border-black/10 rounded-xl shadow-md h-full w-full p-6">
            <h2 class="text-xl font-semibold mb-4">Selamat Datang, Administrator!</h2>
            <div class="flex gap-6">
                <div class="w-3/4">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="flex flex-col items-center w-1/4 h-full">
                    <h3 class="font-semibold mb-4">Persentase Penjualan Produk</h3>
                    <canvas id="pieChart" width="250" height="250" class="mt-2"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart
        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: @json($data),
                    borderWidth: 1,
                    backgroundColor: 'rgba(54, 162, 235, 0.3)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 160
                    }
                }
            }
        });

        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: @json($pieLabels),
                datasets: [{
                    label: 'Persentase Penjualan Produk',
                    data: @json($pieData),
                    backgroundColor: [
                        '#4f46e5', '#0ea5e9', '#10b981', '#f59e0b',
                        '#ef4444', '#6366f1', '#14b8a6', '#3b82f6',
                        '#84cc16', '#eab308', '#a855f7'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
