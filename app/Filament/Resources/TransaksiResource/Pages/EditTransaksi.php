<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TransaksiResource;

class EditTransaksi extends EditRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Transaksi Diperbarui')
            ->body('Data transaksi telah berhasil diperbarui dalam tabel.')
            ->icon('heroicon-o-document-check')
            ->color('warning')
            ->iconColor('warning')
            ->duration(5000);
    }
}
