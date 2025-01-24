<?php

namespace App\Filament\Widgets;

use App\Models\Mobil;
use App\Models\Customer;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    // protected ?string $heading = 'Dashboard Carent';

    // protected ?string $description = 'An overview of some analytics.';

    protected function getStats(): array
    {
        $mobil_count = Mobil::get()->where('status', 'tersedia')->count();
        $customer_count = Customer::count();
        $transaksi_count = Transaksi::count();
        return [

            Stat::make('Jumlah Mobil', $mobil_count.' Mobil')
                ->icon('heroicon-o-truck')
                ->description('Jumlah mobil yang tersedia')
                ->color('success'),
            Stat::make('Jumlah Customer', $customer_count.' Orang')
                ->description('Jumlah customer yang terdaftar')
                ->icon('heroicon-o-user-group')
                ->color('info'),
            Stat::make('Jumlah Transaksi', $transaksi_count.' Transaksi')
                ->icon('heroicon-o-banknotes')
                ->description('Jumlah transaksi yang terjadi')
                ->color('warning'),

        ];
    }
}
