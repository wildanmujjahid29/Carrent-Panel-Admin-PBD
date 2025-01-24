<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomerResource;
use Filament\Notifications\Notification;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Tambah Data Customer';
    protected function getRedirectUrl(): string
    {
        // Ubah URL sesuai kebutuhan
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode transaksi
        $last = Customer::orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = intval(substr($last->kode_customer, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['kode_customer'] = 'CMR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        
        return $data;
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Data Customer Ditambahkan')
            ->body('Data customer telah berhasil disimpan dalam tabel.')
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->iconColor('success')
            ->duration(5000);
    }
}
