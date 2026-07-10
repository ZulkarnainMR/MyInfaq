<?php

namespace App\Filament\Staf\Resources;

use App\Filament\Staf\Resources\KeteluasanResource\Pages;
use App\Models\Ketelusan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KetelusanResource extends Resource
{
    protected static ?string $model = Ketelusan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationLabel = 'Audit Ketelusan';
    protected static ?string $modelLabel = 'Laporan Ketelusan';
    protected static ?string $pluralModelLabel = 'Laporan Ketelusan';
    protected static ?string $navigationGroup = 'Kutipan & Kempen';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Laporan Agihan')->schema([
                Forms\Components\Select::make('id_kempen')
                    ->label('Kempen')
                    ->relationship('kempen', 'tajuk_kempen')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('tajuk_laporan')
                    ->label('Tajuk Laporan')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tarikh_agihan')
                    ->label('Tarikh Agihan')
                    ->required(),
                Forms\Components\TextInput::make('bilangan_penerima')
                    ->label('Bilangan Penerima')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Textarea::make('keterangan_penerima')
                    ->label('Keterangan Penerima')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('gambar_agihan')
                    ->label('Gambar Agihan')
                    ->image()
                    ->multiple()
                    ->directory('ketelusan-images')
                    ->maxFiles(10)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Keputusan Audit')->schema([
                Forms\Components\Select::make('status_audit')
                    ->label('Status Audit')
                    ->options([
                        'Pending'    => 'Pending',
                        'Diluluskan' => 'Diluluskan',
                        'Ditolak'    => 'Ditolak',
                    ]),
                Forms\Components\Textarea::make('nota_audit')
                    ->label('Nota Audit')
                    ->rows(3),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_ketelusan')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('kempen.tajuk_kempen')
                    ->label('Kempen')->searchable()->limit(35),
                Tables\Columns\TextColumn::make('tajuk_laporan')->label('Tajuk Laporan')->limit(30),
                Tables\Columns\TextColumn::make('tarikh_agihan')->label('Tarikh')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('bilangan_penerima')->label('Penerima')->sortable(),
                Tables\Columns\BadgeColumn::make('status_audit')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Diluluskan',
                        'danger'  => 'Ditolak',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_audit')
                    ->options(['Pending' => 'Pending', 'Diluluskan' => 'Diluluskan', 'Ditolak' => 'Ditolak']),
            ])
            ->actions([
                Tables\Actions\Action::make('luluskan')
                    ->label('Luluskan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Ketelusan $record) => $record->status_audit === 'Pending')
                    ->requiresConfirmation()
                    ->action(function (Ketelusan $record) {
                        $record->update([
                            'status_audit' => 'Diluluskan',
                            'id_staf'      => auth()->user()->staf?->id_staf,
                            'tarikh_audit' => now(),
                        ]);
                        Notification::make()->title('Laporan ketelusan diluluskan!')->success()->send();
                    }),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Ketelusan $record) => $record->status_audit === 'Pending')
                    ->form([
                        Forms\Components\Textarea::make('nota_audit')->label('Nota Penolakan')->required(),
                    ])
                    ->action(function (Ketelusan $record, array $data) {
                        $record->update([
                            'status_audit' => 'Ditolak',
                            'nota_audit'   => $data['nota_audit'],
                            'id_staf'      => auth()->user()->staf?->id_staf,
                            'tarikh_audit' => now(),
                        ]);
                        Notification::make()->title('Laporan ditolak.')->warning()->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKetelusan::route('/'),
            'create' => Pages\CreateKetelusan::route('/create'),
            'edit'   => Pages\EditKetelusan::route('/{record}/edit'),
            'view'   => Pages\ViewKetelusan::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Ketelusan::where('status_audit', 'Pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
