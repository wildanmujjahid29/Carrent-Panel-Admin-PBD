<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use App\Models\TransaksiDetail;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\TransaksiExporter;
use Filament\Tables\Filters\DateRangeFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiDetailResource\Pages;
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
                TextInput::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->required()
                    ->disabled(),

                Select::make('mobil_id')
                    ->label('Nama Mobil')
                    ->relationship('mobil', 'nama_mobil')
                    ->required(),

                Select::make('mobil_id')
                    ->label('Plat Nomor')
                    ->relationship('mobil', 'plat_nomor')
                    ->required()
                    ->disabled(),

                Select::make('customer_id')
                    ->label('Nama Customer')
                    ->relationship('customer', 'nama_customer')
                    ->required(),

                DatePicker::make('tanggal_sewa')
                    ->label('Tanggal Sewa')
                    ->required(),

                TextInput::make('lama_peminjaman')
                    ->label('Lama Pinjam (hari)')
                    ->numeric()
                    ->required(),

                DatePicker::make('tanggal_kembali')
                    ->label('Tanggal Kembali'),

                TextInput::make('total_harga')
                    ->label('Total Harga Sewa')
                    ->numeric()
                    ->disabled(),

                TextInput::make('denda')
                    ->label('Denda')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->label('Kode Transaksi')->copyable(),
                TextColumn::make('mobil.nama_mobil')->label('Nama Mobil')->copyable(),
                TextColumn::make('mobil.plat_nomor')->label('Plat Nomor')->copyable(),
                TextColumn::make('customer.nama_customer')->label('Nama Customer')->copyable(),
                TextColumn::make('customer.no_hp')->label('No. HP')->copyable(),
                TextColumn::make('tanggal_sewa')->label('Tanggal Sewa')->date()->copyable(),
                TextColumn::make('lama_peminjaman')->label('Durasi Sewa')->formatStateUsing(fn($state) => $state . ' hari'),
                TextColumn::make('tanggal_kembali')->label('Tanggal Kembali')->date()->copyable(),
                TextColumn::make('total_harga')->label('Total Harga Sewa')->money('IDR', true)->copyable(),
                TextColumn::make('denda')->label('Denda')->money('IDR', true)->copyable(),
            ])
            ->filters([
                Filter::make('tanggal_sewa')
                ->form([
                    DatePicker::make('from')->label('Dari'),
                    DatePicker::make('to')->label('Sampai'),
                ])
                ->query(function ($query, $data) {
                    return $query
                        ->when($data['from'], fn ($q) => $q->whereDate('tanggal_sewa', '>=', $data['from']))
                        ->when($data['to'], fn ($q) => $q->whereDate('tanggal_sewa', '<=', $data['to']));
                })
                ->label('Rentang Tanggal Sewa')
                ->indicateUsing(function ($data) {
                    if ($data['from'] && $data['to']) {
                        return 'Rentang Tanggal Sewa: ' . $data['from'] . ' - ' . $data['to'];
                    } elseif ($data['from']) {
                        return 'Tanggal Sewa dari: ' . $data['from'];
                    } elseif ($data['to']) {
                        return 'Tanggal Sewa sampai: ' . $data['to'];
                    }
                    return null;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make('Export Transaksi')
                    ->exporter(TransaksiExporter::class)
                    ->color('success'),
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
