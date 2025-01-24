<?php

namespace App\Filament\Resources\KembaliResource\Pages;

use Filament\Actions;
use App\Models\Transaksi;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\KembaliResource;

class ListKembalis extends ListRecords
{
    protected static string $resource = KembaliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        // Pastikan hanya data dengan status 'sewa' yang diambil
        return Transaksi::query()->where('status', 'kembali');
    }
}
