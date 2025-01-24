<?php

namespace App\Filament\Resources\MobilResource\Pages;
use Filament\Notifications\Notification;

use App\Models\Mobil;
use Filament\Actions;
use App\Filament\Resources\MobilResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMobil extends CreateRecord
{
    protected static string $resource = MobilResource::class;
    protected static ?string $title = 'Tambah Data Mobil';
    protected function getRedirectUrl(): string
    {
        // Ubah URL sesuai kebutuhan
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode transaksi
        $last = Mobil::orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = intval(substr($last->kode_mobil, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['kode_mobil'] = 'MBL' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Mobil Ditambahkan')
            ->body('Data mobil telah berhasil disimpan dalam tabel.')
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->iconColor('success')
            ->duration(5000);
    }
}
