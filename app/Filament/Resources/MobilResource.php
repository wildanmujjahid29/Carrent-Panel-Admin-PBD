<?php

namespace App\Filament\Resources;

use Filament\Forms;
use PhpOption\None;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MobilResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{Grid, Group, Section};
use App\Filament\Resources\MobilResource\RelationManagers;

class MobilResource extends Resource
{
    protected static ?string $model = Mobil::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Mobil';
    protected static ?string $slug = 'mobil';
    protected static ?string $label = 'Kelola Data Mobil';
    protected static ?string $navigationGroup = 'Kelola Data';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Mobil')
                ->schema([
                    Grid::make(2) // Dua kolom
                        ->schema([
                            TextInput::make('kode_mobil')
                                ->unique(ignoreRecord: true)
                                ->disabled()
                                ->dehydrated(false)
                                ->hidden(),
                                
                            TextInput::make('nama_mobil')
                                ->label('Nama Mobil')
                                ->placeholder('Masukan Nama Mobil...')
                                ->required(),
                        ]),

                    Grid::make(3) // Tiga kolom
                        ->schema([
                            TextInput::make('merk')
                                ->label('Merk')
                                ->placeholder('Masukan Merk Mobil...')
                                ->datalist([
                                    'BWM',
                                    'Ford',
                                    'Mercedes-Benz',
                                    'Porsche',
                                    'Toyota',
                                    'Tesla',
                                    'Volkswagen',
                                ]),

                            TextInput::make('warna')
                                ->label('Warna')
                                ->placeholder('Masukan Warna...')
                                ->datalist([
                                    'Hitam',
                                    'Putih',
                                    'Merah',
                                    'Biru',
                                    'Hijau',
                                    'Kuning',
                                    'Orange',
                                    'Ungu',
                                    'Coklat',
                                    'Abu-abu',
                                ]),

                            TextInput::make('tahun')
                                ->label('Tahun')
                                ->placeholder('Masukan Tahun Mobil...'),
                        ]),
                ])
                ->columns(1),

            Section::make('Detail Lainnya')
                ->schema([
                    Grid::make(2) // Dua kolom
                        ->schema([
                            TextInput::make('plat_nomor')
                                ->label('Plat Nomor')
                                ->placeholder('Masukan Plat Nomor...')
                                ->required(),

                            Select::make('kategori_id')
                                ->relationship(name: 'kategori', titleAttribute: 'nama_kategori')
                                ->label('Kategori')
                                ->placeholder('Pilih Kategori'),
                        ]),

                    TextInput::make('harga')
                        ->label('Harga Sewa')
                        ->placeholder('Masukan Harga Sewa...')
                        ->required()
                        ->prefix('Rp'),

                    Select::make('status')
                        ->label('Status')
                        ->placeholder('Pilih Status')
                        ->options([
                            'tersedia' => 'Tersedia',
                            'disewa' => 'Disewa',
                        ]),

                    FileUpload::make('gambar')
                        ->image()
                        ->label('Gambar')
                        ->helperText('Unggah gambar mobil (Opsional).'),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn::make('gambar')
                //     ->label('Gambar')
                //     ->circular()
                //     ->defaultImageUrl(url('/image/placeholder.png')),
                TextColumn::make('kode_mobil')
                    ->label('Kode Mobil')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('nama_mobil')
                    ->label('Nama Mobil')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('merk')
                    ->label('Merk')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('warna')
                    ->label('Warna')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('tahun')
                    ->label('Tahun')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('harga')
                    ->label('Harga Sewa')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'tersedia' => 'success', // Warna hijau
                        'disewa' => 'warning',   // Warna default (abu-abu)
                    })
                    ->sortable()
                    ->copyable(true),
            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'tersedia' => 'Tersedia',
                    'disewa' => 'Disewa',
                ])
                ->attribute('status')
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Ubah Data'),
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data')
                    ->modalHeading('Data Mobil Akan Dihapus')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Mobil Dihapus')
                            ->body('Data mobil telah berhasil dihapus dari tabel.')
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
            'index' => Pages\ListMobils::route('/'),
            'create' => Pages\CreateMobil::route('/create'),
            'edit' => Pages\EditMobil::route('/{record}/edit'),
        ];
    }
}
