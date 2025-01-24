<?php

namespace App\Filament\Resources\TransaksiDetailResource\Pages;

use App\Filament\Resources\TransaksiDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaksiDetail extends EditRecord
{
    protected static string $resource = TransaksiDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
