<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class BarChart extends ChartWidget
{
    protected static ?string $heading = 'Mobil Paling Banyak Disewa';

    protected function getData(): array
    {
        // Query untuk mendapatkan 5 mobil paling banyak disewa
        $data = Transaksi::join('mobils', 'transaksis.mobil_id', '=', 'mobils.id') // Sesuaikan nama tabel dan kolom
            ->select('mobils.nama_mobil as mobil', DB::raw('COUNT(transaksis.id) as total')) // Sesuaikan nama kolom
            ->groupBy('mobils.nama_mobil')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Menyiapkan data untuk chart
        $labels = $data->pluck('mobil')->toArray();
        $totals = $data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Sewa',
                    'data' => $totals,
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Sewa'
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => false,
                        'text' => 'Nama Mobil'
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
