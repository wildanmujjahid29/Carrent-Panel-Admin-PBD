<?php 

namespace App\Filament\Resources\KembaliResource\Pages;

use App\Filament\Resources\KembaliResource;
use Filament\Actions\Action; 
use Filament\Resources\Pages\ViewRecord;

class ViewKembali extends ViewRecord
{
    protected static string $resource = KembaliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kembali') // Gunakan class Action yang sudah di-import
                ->label('Kembali')
                ->url($this->getResource()::getUrl('index')) // Redirect ke halaman index
                ->color('gray'), // Warna tombol
        ];
    }
}