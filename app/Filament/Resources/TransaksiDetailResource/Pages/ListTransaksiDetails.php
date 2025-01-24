<?php

namespace App\Filament\Resources\TransaksiDetailResource\Pages;

use App\Filament\Resources\TransaksiDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksiDetails extends ListRecords
{
    protected static string $resource = TransaksiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
