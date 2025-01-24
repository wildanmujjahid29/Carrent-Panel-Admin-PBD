<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Models\Mobil;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransaksiResource;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    protected static ?string $title = 'Tambah Transaksi Sewa Mobil';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function beforeCreate(): void
    // {
    //     // Validate tanggal
    //     $tanggal_sewa = strtotime($this->data['tanggal_sewa']);
    //     $tanggal_kembali = strtotime($this->data['tanggal_kembali']);
        
    //     if ($tanggal_sewa > $tanggal_kembali) {
    //         $this->halt();
    //         $this->notify('error', 'Tanggal sewa tidak boleh lebih besar dari tanggal kembali');
    //     }
    // }

    protected function afterCreate(): void
    {
        // Update status mobil menjadi 'disewa'
        $transaksi = $this->record;
        $mobil = $transaksi->mobil;
        $mobil->status = 'disewa';
        $mobil->save();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode transaksi
        $last = \App\Models\Transaksi::orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = intval(substr($last->kode_transaksi, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $data['kode_transaksi'] = 'TRX' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Hitung total harga
        $mobil = Mobil::find($data['mobil_id']); 
        $lama_peminjaman = $data['lama_peminjaman'] ?? 1;
        $denda = $data['denda'] ?? 0;
        $data['total_harga'] = $mobil->harga * $lama_peminjaman + $denda; 
        
        return $data;
    }
}
