<?php

namespace App\Filament\Staf\Resources;

use App\Filament\Staf\Resources\KempenResource\Pages;
use App\Models\Kempen;
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Staf\Resources\KempenResource\RelationManagers\UpdatesRelationManager;

class KempenResource extends Resource
{
    protected static ?string $model = Kempen::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Kempen';
    protected static ?string $modelLabel = 'Kempen';
    protected static ?string $pluralModelLabel = 'Semua Kempen';
    protected static ?string $navigationGroup = 'Kutipan & Kempen';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Kempen')
                ->schema([
                    Forms\Components\TextInput::make('tajuk_kempen')
                        ->label('Tajuk Kempen')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'Pendidikan' => 'Pendidikan',
                            'Kesihatan' => 'Kesihatan',
                            'Bencana Alam' => 'Bencana Alam',
                            'Anak Yatim' => 'Anak Yatim',
                            'Fakir Miskin' => 'Fakir Miskin',
                            'Lain-lain' => 'Lain-lain',
                        ])
                        ->default('Lain-lain')
                        ->required(),
                    Forms\Components\Textarea::make('keterangan_kempen')
                        ->label('Keterangan')
                        ->required()
                        ->rows(4),
                    Forms\Components\TextInput::make('sasaran_dana')
                        ->label('Sasaran Dana (RM)')
                        ->numeric()
                        ->required()
                        ->prefix('RM'),
                    Forms\Components\DatePicker::make('tarikh_tamat')
                        ->label('Tarikh Tamat'),
                    Forms\Components\FileUpload::make('gambar_kempen')
                        ->label('Gambar Kempen')
                        ->image()
                        ->directory('kempen-images'),
                ])->columns(2),

            Forms\Components\Section::make('Status & Audit')
                ->schema([
                    Forms\Components\Placeholder::make('jumlah_kutipan_semasa')
                        ->label('Terkumpul Semasa')
                        ->content(fn (?Kempen $record): string => $record ? 'RM ' . number_format($record->jumlah_kutipan_semasa, 2) : '-')
                        ->visible(fn (?Kempen $record): bool => $record !== null),
                    Forms\Components\Placeholder::make('amaun_bersih_ngo')
                        ->label('Amaun Bersih (Kepada Organisasi)')
                        ->content(fn (?Kempen $record): string => $record ? 'RM ' . number_format($record->amaun_bersih_ngo, 2) : '-')
                        ->visible(fn (?Kempen $record): bool => $record !== null),
                    Forms\Components\Select::make('status_kempen')
                        ->label('Status')
                        ->options([
                            'Pending'  => 'Pending',
                            'Aktif'    => 'Aktif',
                            'Ditolak'  => 'Ditolak',
                            'Selesai'  => 'Selesai',
                            'Dibayar'  => 'Dibayar',
                        ])
                        ->required(),
                    Forms\Components\Select::make('id_staf')
                        ->label('Disemak Oleh')
                        ->options(Staf::all()->pluck('nama_staf', 'id_staf'))
                        ->nullable(),
                    Forms\Components\Textarea::make('sebab_tolak')
                        ->label('Sebab Ditolak')
                        ->rows(3)
                        ->visible(fn ($get) => $get('status_kempen') === 'Ditolak'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kempen')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('gambar_kempen')
                    ->label('Gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('tajuk_kempen')
                    ->label('Tajuk')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('organisasi.nama_organisasi')
                    ->label('Organisasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sasaran_dana')
                    ->label('Sasaran (RM)')
                    ->money('MYR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_kutipan_semasa')
                    ->label('Terkumpul (RM)')
                    ->money('MYR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amaun_bersih_ngo')
                    ->label('Amaun Bersih (RM)')
                    ->money('MYR'),
                Tables\Columns\BadgeColumn::make('status_kempen')
                    ->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Aktif',
                        'danger'  => 'Ditolak',
                        'info'    => 'Selesai',
                        'primary' => 'Dibayar',
                    ]),
                Tables\Columns\TextColumn::make('tarikh_tamat')
                    ->label('Tarikh Tamat')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_kempen')
                    ->label('Status')
                    ->options([
                        'Pending'  => 'Pending',
                        'Aktif'    => 'Aktif',
                        'Ditolak'  => 'Ditolak',
                        'Selesai'  => 'Selesai',
                        'Dibayar'  => 'Dibayar',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('luluskan')
                    ->label('Luluskan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Kempen $record) => $record->status_kempen === 'Pending')
                    ->requiresConfirmation()
                    ->modalHeading('Luluskan Kempen')
                    ->modalDescription('Adakah anda pasti ingin meluluskan kempen ini?')
                    ->modalSubmitActionLabel('Ya, Luluskan')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Kempen $record) {
                        $record->update([
                            'status_kempen'  => 'Aktif',
                            'id_staf'        => auth()->user()->staf?->id_staf,
                            'tarikh_semakan' => now(),
                        ]);
                        Notification::make()->title('Kempen diluluskan!')->success()->send();
                    }),

                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Kempen $record) => $record->status_kempen === 'Pending')
                    ->form([
                        Forms\Components\Textarea::make('sebab_tolak')
                            ->label('Sebab Ditolak')
                            ->required(),
                    ])
                    ->modalHeading('Tolak Kempen')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (Kempen $record, array $data) {
                        $record->update([
                            'status_kempen'  => 'Ditolak',
                            'sebab_tolak'    => $data['sebab_tolak'],
                            'id_staf'        => auth()->user()->staf?->id_staf,
                            'tarikh_semakan' => now(),
                        ]);
                        Notification::make()->title('Kempen ditolak.')->warning()->send();
                    }),

                Tables\Actions\Action::make('luluskan_bayaran')
                    ->label('Luluskan Bayaran')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (Kempen $record) => $record->bayaran_diminta && !$record->bayaran_diluluskan)
                    ->requiresConfirmation()
                    ->action(function (Kempen $record) {
                        $record->update([
                            'bayaran_diluluskan'        => true,
                            'tarikh_bayaran_diluluskan' => now(),
                            'status_kempen'             => 'Dibayar',
                        ]);
                        Notification::make()->title('Bayaran diluluskan!')->success()->send();
                    }),

                Tables\Actions\EditAction::make()->label('Kemaskini'),
                Tables\Actions\ViewAction::make()->label('Lihat'),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKempen::route('/'),
            'create' => Pages\CreateKempen::route('/create'),
            'edit'   => Pages\EditKempen::route('/{record}/edit'),
            'view'   => Pages\ViewKempen::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            UpdatesRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Kempen::where('status_kempen', 'Pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
