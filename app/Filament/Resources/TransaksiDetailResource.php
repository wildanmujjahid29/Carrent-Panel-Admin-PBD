<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\TransaksiDetail;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiDetailResource\Pages;
use App\Filament\Exports\ProductExporter;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Resources\TransaksiDetailResource\RelationManagers;

class TransaksiDetailResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Laporan Transaksi';
    protected static ?string $slug = 'detail-transaksi';
    protected static ?string $label = 'Data Transaksi Sewa Mobil'; 
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 4;

    public static function canCreate(): bool
    {
        return false; // Menonaktifkan tombol create
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_transaksi')
                ->label('Kode Transaksi')
                ->required()
                ->disabled(),

            Forms\Components\Select::make('mobil_id')
                ->label('Nama Mobil')
                ->relationship('mobil', 'nama_mobil')
                ->required(),

            Forms\Components\Select::make('mobil_id')
                ->label('Plat Nomor')
                ->relationship('mobil', 'plat_nomor')
                ->required()
                ->disabled(),

            Forms\Components\Select::make('customer_id')
                ->label('Nama Customer')
                ->relationship('customer', 'nama_customer')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_sewa')
                ->label('Tanggal Sewa')
                ->required(),

            Forms\Components\TextInput::make('lama_peminjaman')
                ->label('Lama Pinjam (hari)')
                ->numeric()
                ->required(),

            Forms\Components\DatePicker::make('tanggal_kembali')
                ->label('Tanggal Kembali'),

            Forms\Components\TextInput::make('total_harga')
                ->label('Total Harga Sewa')
                ->numeric()
                ->disabled(),

            Forms\Components\TextInput::make('denda')
                ->label('Denda')
                ->numeric()
                ->default(0),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'sewa' => 'Sewa',
                    'kembali' => 'Kembali',
                ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ExportAction::make()
                    ->exporter(Transaksi::class)
            ])
            ->columns([
                TextColumn::make('kode_transaksi')->label('Kode Transaksi'),
                TextColumn::make('mobil.nama_mobil')->label('Nama Mobil'),
                TextColumn::make('mobil.plat_nomor')->label('Plat Nomor'),
                TextColumn::make('customer.nama_customer')->label('Nama Customer'),
                TextColumn::make('customer.no_hp')->label('No. HP'),
                TextColumn::make('tanggal_sewa')->label('Tanggal Sewa')->date(),
                TextColumn::make('lama_peminjaman')->label('Durasi Sewa')->formatStateUsing(fn ($state) => $state . ' hari'),
                TextColumn::make('tanggal_kembali')->label('Tanggal Kembali')->date(),
                TextColumn::make('total_harga')->label('Total Harga Sewa')->money('IDR', true),
                TextColumn::make('denda')->label('Denda')->money('IDR', true),
                TextColumn::make('status')->label('Status'),  
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransaksiDetails::route('/'),
            'create' => Pages\CreateTransaksiDetail::route('/create'),
            'edit' => Pages\EditTransaksiDetail::route('/{record}/edit'),
        ];
    }
}
