<?php

namespace App\Filament\Resources\MobilResource\Pages;

use App\Filament\Resources\MobilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditMobil extends EditRecord
{
    protected static string $resource = MobilResource::class;
    protected static ?string $title = 'Ubah Data Mobil';
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading('Data Mobil Akan Dihapus'),
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
            ->title('Data Mobil Diperbarui')
            ->body('Data mobil telah berhasil diperbarui dalam tabel.')
            ->icon('heroicon-o-document-check')
            ->color('warning')
            ->iconColor('warning')
            ->duration(5000);
    }
}
