<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kembali;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\KembaliResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KembaliResource\RelationManagers;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
class KembaliResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    public static function canCreate(): bool
    {
        return false; // Menonaktifkan tombol create
    }

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'Pengembalian';
    protected static ?string $slug = 'kembali';
    protected static ?string $label = 'Pengembalian Mobil';
    protected static ?string $navigationGroup = 'Kelola Transaksi';    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('kode_transaksi', 'desc')        
            ->columns([
                TextColumn::make('kode_transaksi')->label('Kode Transaksi')->sortable('desc')->copyable()->searchable(),
                TextColumn::make('mobil.nama_mobil')->label('Nama Mobil')->copyable()->searchable(),
                TextColumn::make('customer.nama_customer')->label('Nama Customer')->copyable()->searchable(),
                TextColumn::make('tanggal_sewa')->label('Tanggal Sewa')->date()->copyable(),
                TextColumn::make('lama_peminjaman')->label('Durasi Sewa')->formatStateUsing(fn ($state) => $state . ' hari')->copyable(),
                TextColumn::make('tanggal_kembali')->label('Tanggal Kembali')->date()->copyable(),
                TextColumn::make('total_harga')->label('Total Harga Sewa')->money('IDR', true)->copyable(),
                TextColumn::make('denda')->label('Denda')->money('IDR', true)->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'kembali' => 'success', // Warna hijau
                        'sewa' => 'warning',   // Warna default (abu-abu)
                    })
                    ->copyable(true),
            ])
            ->emptyStateHeading('Data Pengembalian Mobil Kosong !!!')
            ->emptyStateDescription('Belum ada pengembalian mobil yang dilakukan.')
            ->emptyStateIcon('heroicon-o-megaphone')

            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Pengembalian Mobil')
                    ->schema([
                        Grid::make(2) // Mengatur tata letak dalam 2 kolom
                            ->schema([
                                TextEntry::make('kode_transaksi')->label('Kode Transaksi')->copyable(),
                                TextEntry::make('mobil.nama_mobil')->label('Nama Mobil')->copyable(),
                                TextEntry::make('customer.nama_customer')->label('Nama Customer')->copyable(),
                                TextEntry::make('tanggal_sewa')->label('Tanggal Sewa')->date(),
                                TextEntry::make('lama_peminjaman')->label('Durasi Sewa')->formatStateUsing(fn ($state) => $state . ' hari'),
                                TextEntry::make('tanggal_kembali')->label('Tanggal Kembali')->date(),
                                TextEntry::make('total_harga')->label('Total Harga Sewa')->money('IDR', true),
                                TextEntry::make('denda')->label('Denda')->money('IDR', true)->helperText('Denda yang dikenakan jika melewati batas waktu sewa'),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn(string $state): string => match (strtolower($state)) {
                                        'kembali' => 'success',
                                        'sewa' => 'warning',
                                    }),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKembalis::route('/'),
            'create' => Pages\CreateKembali::route('/create'),
            'edit' => Pages\EditKembali::route('/{record}/edit'),
            'view' => Pages\ViewKembali::route('/{record}'),
        ];
    }
}
