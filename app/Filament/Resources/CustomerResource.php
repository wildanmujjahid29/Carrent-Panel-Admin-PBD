<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CustomerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $slug = 'customer';
    protected static ?string $label = 'Kelola Data Customer';
    protected static ?string $navigationGroup = 'Kelola Data';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Customer')
                ->schema([
                    TextInput::make('kode_customer')
                        ->unique(ignoreRecord: true)
                        ->disabled()
                        ->dehydrated(false)
                        ->hidden(),
    
                    TextInput::make('nama_customer')
                        ->label('Nama Customer')
                        ->placeholder('Masukan Nama Customer...')
                        ->required()
                        ->columnSpan(2),
    
                    TextInput::make('alamat')
                        ->label('Alamat')
                        ->placeholder('Masukan Alamat...')
                        ->required()
                        ->columnSpan(3),
    
                    TextInput::make('rt')
                        ->label('RT')
                        ->placeholder('Masukan RT...')
                        ->required()
                        ->columnSpan(1),
    
                    TextInput::make('rw')
                        ->label('RW')
                        ->placeholder('Masukan RW...')
                        ->required()
                        ->columnSpan(1),
    
                    TextInput::make('desa')
                        ->label('Kelurahan/Desa')
                        ->placeholder('Masukan Desa...')
                        ->required()
                        ->columnSpan(2),
    
                    TextInput::make('kecamatan')
                        ->label('Kecamatan')
                        ->placeholder('Masukan Kecamatan...')
                        ->required()
                        ->columnSpan(2),
    
                    TextInput::make('kota')
                        ->label('Kota/Kabupaten')
                        ->placeholder('Masukan Kota...')
                        ->required()
                        ->columnSpan(2),
    
                    TextInput::make('kode_pos')
                        ->label('Kode Pos')
                        ->placeholder('Masukan Kode Pos...')
                        ->columnSpan(1),
    
                    TextInput::make('no_hp')
                        ->tel()
                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                        ->label('No HP')
                        ->placeholder('Masukan No HP...')
                        ->required()
                        ->columnSpan(2),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_customer')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('nama_customer')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('full_address')
                    ->label('Alamat')
                    ->alignCenter()
                    ->searchable()
                    ->copyable(true)
                    ->getStateUsing(fn ($record) => 
                        $record->alamat . ', RT ' . $record->rt . ', RW ' . $record->rw . ', ' . 
                        $record->desa . ', ' . $record->kecamatan
                    ),
                TextColumn::make('kota')
                    ->label('Kota')
                    ->searchable()
                    ->sortable()
                    ->copyable(true),
                TextColumn::make('kode_pos')
                    ->label('Kode Pos')
                    ->default('-')
                    ->searchable()
                    ->copyable(true),
                TextColumn::make('no_hp')
                    ->label('No HP')
                    ->searchable()
                    ->copyable(true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Ubah Data'),
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data')
                    ->modalHeading('Data Customer Akan Dihapus')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Customer Dihapus')
                            ->body('Data Customer telah berhasil dihapus dari tabel.')
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
