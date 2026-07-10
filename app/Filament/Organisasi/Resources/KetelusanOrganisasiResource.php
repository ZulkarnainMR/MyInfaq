<?php

namespace App\Filament\Organisasi\Resources;

use App\Filament\Organisasi\Resources\KetelusanOrganisasiResource\Pages;
use App\Models\Kempen;
use App\Models\Ketelusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KetelusanOrganisasiResource extends Resource
{
    protected static ?string $model = Ketelusan::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Laporan Ketelusan';
    protected static ?string $modelLabel = 'Laporan Ketelusan';
    protected static ?string $pluralModelLabel = 'Laporan Ketelusan';

    public static function getEloquentQuery(): Builder
    {
        $orgId = auth()->user()->organisasi?->id_organisasi;
        return parent::getEloquentQuery()
            ->whereHas('kempen', fn ($q) => $q->where('id_organisasi', $orgId));
    }

    public static function form(Form $form): Form
    {
        $orgId = auth()->user()->organisasi?->id_organisasi;
        return $form->schema([
            Forms\Components\Section::make('Laporan Agihan Dana')->schema([
                Forms\Components\Select::make('id_kempen')
                    ->label('Kempen')
                    ->options(
                        Kempen::where('id_organisasi', $orgId)
                            ->whereIn('status_kempen', ['Aktif', 'Selesai', 'Dibayar'])
                            ->pluck('tajuk_kempen', 'id_kempen')
                    )
                    ->required()->searchable(),
                Forms\Components\TextInput::make('tajuk_laporan')
                    ->label('Tajuk Laporan')->maxLength(255),
                Forms\Components\DatePicker::make('tarikh_agihan')
                    ->label('Tarikh Agihan')->required(),
                Forms\Components\TextInput::make('bilangan_penerima')
                    ->label('Bilangan Penerima')->numeric()->minValue(0),
                Forms\Components\Textarea::make('keterangan_penerima')
                    ->label('Keterangan Penerima')
                    ->required()->rows(5)->columnSpanFull(),
                Forms\Components\FileUpload::make('gambar_agihan')
                    ->label('Gambar Agihan (Muat Naik Sehingga 10)')
                    ->image()->multiple()->directory('ketelusan-images')
                    ->maxFiles(10)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kempen.tajuk_kempen')->label('Kempen')->limit(35),
                Tables\Columns\TextColumn::make('tajuk_laporan')->label('Tajuk Laporan')->limit(30),
                Tables\Columns\TextColumn::make('tarikh_agihan')->label('Tarikh')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('bilangan_penerima')->label('Penerima'),
                Tables\Columns\BadgeColumn::make('status_audit')->label('Status Audit')
                    ->colors(['warning' => 'Pending', 'success' => 'Diluluskan', 'danger' => 'Ditolak']),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Ketelusan $record) => $record->status_audit === 'Pending'),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKetelusanOrganisasi::route('/'),
            'create' => Pages\CreateKetelusanOrganisasi::route('/create'),
            'edit'   => Pages\EditKetelusanOrganisasi::route('/{record}/edit'),
            'view'   => Pages\ViewKetelusanOrganisasi::route('/{record}'),
        ];
    }
}
