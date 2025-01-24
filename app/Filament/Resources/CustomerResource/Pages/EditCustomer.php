<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomerResource;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'Ubah Data Customer';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('Data Customer Akan Dihapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Arahkan ke halaman index setelah pengeditan selesai
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Customer Diperbarui')
            ->body('Data customer telah berhasil diperbarui dalam tabel.')
            ->icon('heroicon-o-document-check')
            ->color('warning')
            ->iconColor('warning')
            ->duration(5000);
    }
}
