<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use Filament\Tables\Actions\Action;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Penyewaan';
    protected static ?string $slug = 'sewa';
    protected static ?string $label = 'Kelola Sewa Mobil';
    protected static ?string $navigationGroup = 'Kelola Transaksi';    
    protected static ?int $navigationSort = 3;

    public static function pengembalianMobil(array $data): array
    {
        // Ambil data transaksi
        $transaksi = Transaksi::find($data['id']);
        $mobil = $transaksi->mobil;

        // Hitung durasi keterlambatan
        $tanggal_kembali = strtotime($data['tanggal_kembali']);
        $tanggal_deadline = strtotime($transaksi->tanggal_sewa . ' +' . $transaksi->lama_peminjaman . ' days');

        $keterlambatan = max(0, ceil(($tanggal_kembali - $tanggal_deadline) / (60 * 60 * 24))); // Hitung keterlambatan dalam hari
        $denda = $keterlambatan * $mobil->harga; // Hitung denda

        // Update transaksi
        $transaksi->update([
            'tanggal_kembali' => date('Y-m-d', $tanggal_kembali),
            'denda' => $denda,
        ]);

        // Ubah status mobil menjadi tersedia
        $mobil->update(['status' => 'tersedia']);

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kode Transaksi yang tidak bisa diedit dan disembunyikan
                TextInput::make('kode_transaksi')
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated(false)
                    ->hidden(),

                // Kolom pertama untuk Mobil
                Grid::make(2)
                    ->schema([
                        Select::make('mobil_id')
                            ->relationship('mobil', 'nama_mobil', function (Builder $query) {
                                return $query->where('status', 'tersedia')
                                ->selectRaw("id, CONCAT(nama_mobil, ' - ', plat_nomor) as nama_mobil");
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $mobil = Mobil::find($state);
                                    $set('harga_per_hari', $mobil->harga);
                                }
                            })
                            ->label('Nama Mobil')
                            ->placeholder('Pilih Mobil'),

                        TextInput::make('harga_per_hari')
                            ->numeric()
                            ->disabled()
                            ->label('Harga Per Hari')
                            ->placeholder('Harga mobil per hari')
                    ]),

                // Kolom kedua untuk Customer
                Grid::make(2)
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'nama_customer')
                            ->required()
                            ->searchable()
                            ->label('Nama Customer')
                            ->placeholder('Pilih Customer.....'),

                        TextInput::make('lama_peminjaman')
                            ->numeric()
                            ->required()
                            ->label('Lama Peminjaman (hari)')
                            ->afterStateUpdated(function ($state, Set $set, $get) {
                                if ($state && $get('harga_per_hari')) {
                                    $set('total_harga', $state * (int) $get('harga_per_hari'));
                                }
                            })
                            ->placeholder('Masukkan lama sewa.....'),

                        DatePicker::make('tanggal_sewa')
                            ->required()
                            ->live()
                            ->label('Tanggal Sewa')
                            ->placeholder('Pilih Tanggal Sewa')
                            ->afterStateUpdated(function ($state, $old, Set $set, $get) {
                                if ($state && $get('tanggal_kembali')) {
                                    $days = date_diff(date_create($state), date_create($get('tanggal_kembali')))->days + 1;
                                    $harga = (int) $get('harga_per_hari');
                                    $set('total_harga', $days * $harga);
                                }
                            }),
                    ]),

                // Tanggal Sewa dan Tanggal Pengembalian
                Grid::make(2)
                    ->schema([
                        // Total Harga
                        TextInput::make('total_harga')
                            ->disabled()
                            ->prefix('Rp')
                            ->helperText('Total biaya untuk penyewaan Otomatis Terisi')
                            ->label('Total Harga')
                            ->formatStateUsing(fn($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                            ->placeholder('Total biaya untuk peminjaman'),
                    ]),

            ])
            ->columns(2);  // Setting 2 kolom untuk form
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_transaksi')->searchable(),
                TextColumn::make('mobil.nama_mobil')->searchable(),
                TextColumn::make('mobil.plat_nomor')->searchable()->label('Plat Nomor'),
                TextColumn::make('customer.nama_customer')->searchable(),
                TextColumn::make('tanggal_sewa')->date(),
                TextColumn::make('lama_peminjaman')->label('Durasi Sewa')->formatStateUsing(fn($state) => $state . ' hari'),
                TextColumn::make('total_harga')->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'kembali' => 'success', // Warna hijau
                        'sewa' => 'warning',   // Warna default (abu-abu)
                    })
                    ->sortable()
                    ->copyable(true),
            
            ])
            
            ->emptyStateHeading('Transaksi Sewa Mobil Kosong !!!')
            ->emptyStateDescription('Belum ada transaksi sewa mobil yang dilakukan.')
            ->emptyStateIcon('heroicon-o-megaphone')
            
            ->filters([
                // SelectFilter::make('status')
                //     ->options([
                //         'sewa' => 'Sewa',
                //     ])
                //     ->label('Status Mobil')
                //     ->placeholder('Pilih Status')
                //     ->default('sewa'),

            ])
            ->emptyStateActions([
                Action::make('create')
                    ->label('Buat Transaksi Sewa')
                    ->url(route('filament.admin.resources.sewa.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),

            ])
            ->actions([

                Tables\Actions\Action::make('kembalikan')
                    ->label(function ($record) {
                        return $record->status === 'kembali' ? 'Dikembalikan' : 'Kembalikan';
                    })

                    ->icon(function ($record) {
                        return $record->status === 'kembali' ? 'heroicon-o-check-circle' : 'heroicon-o-arrow-left-circle';
                    })
                    ->form([
                        DatePicker::make('tanggal_kembali')
                            ->required()
                            ->label('Tanggal Pengembalian'),
                    ])
                    ->action(function (array $data, Transaksi $record) {
                        TransaksiResource::pengembalianMobil([
                            'id' => $record->id,
                            'tanggal_kembali' => $data['tanggal_kembali'],
                        ]);
                        $record->update([
                            'status' => 'kembali',
                            'tanggal_kembali' => $data['tanggal_kembali'],
                        ]);
                        return redirect()->route('filament.admin.resources.kembali.view', ['record' => $record->id]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Kembalikan Mobil ?')
                    ->modalDescription('Pastikan mobil sudah dikembalikan oleh customer')
                    ->modalSubmitActionLabel('Ya, Kembalikan')
                    ->color(function ($record) {
                        return $record->status === 'kembali' ? 'success' : 'warning';
                    })
                    ->disabled(fn($record) => $record->status === 'kembali'),

                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Edit Transaksi'),
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Transaksi')
                    ->modalHeading('Data Transaksi Sewa Mobil Akan Dihapus')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Transaksi Sewa Dihapus')
                            ->body('Data transaksi sewa telah berhasil dihapus dari tabel.')
                            ->icon('heroicon-o-document-minus')
                            ->color('danger')
                            ->iconColor('danger'),
                    ),
                
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }


}
