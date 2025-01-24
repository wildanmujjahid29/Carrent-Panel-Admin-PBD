<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DashboardChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Transaksi Tahun Ini';
    
    protected function getData(): array
    {
        $data = Transaksi::select(
            DB::raw('MONTH(tanggal_sewa) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('tanggal_sewa', $this->selectedYear ?? date('Y'))
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Memastikan ada data untuk semua bulan (1-12)
        $monthlyData = array_fill(1, 12, 0);
        foreach ($data as $item) {
            $monthlyData[$item->bulan] = $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaksi',
                    'data' => array_values($monthlyData),
                    'borderColor' => '#36A2EB',
                    'tension' => 0.4,
                    'fill' => false,
                ]
            ],
            'labels' => [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Transaksi'
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan'
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                    'position' => 'top'
                ],
                'tooltip' => [
                    'enabled' => true
                ]
            ]
        ];
    }
}