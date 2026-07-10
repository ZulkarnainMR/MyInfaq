<?php

namespace App\Filament\Organisasi\Resources;

use App\Filament\Organisasi\Resources\KempenOrganisasiResource\Pages;
use App\Models\Kempen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Organisasi\Resources\KempenOrganisasiResource\RelationManagers\UpdatesRelationManager;

class KempenOrganisasiResource extends Resource
{
    protected static ?string $model = Kempen::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Kempen Saya';
    protected static ?string $modelLabel = 'Kempen';
    protected static ?string $pluralModelLabel = 'Kempen Saya';


//ADD (create)& Upadate
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Maklumat Kempen')->schema([
                Forms\Components\TextInput::make('tajuk_kempen')
                    ->label('Tajuk Kempen')->required()->maxLength(255),
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
                    ->label('Keterangan Kempen')->required()->rows(5)->columnSpanFull(),
                Forms\Components\TextInput::make('sasaran_dana')
                    ->label('Sasaran Dana (RM)')->numeric()->required()->prefix('RM'),
                Forms\Components\DatePicker::make('tarikh_tamat')
                    ->label('Tarikh Tamat')->required(),
                Forms\Components\FileUpload::make('gambar_kempen')
                    ->label('Gambar Kempen')->image()->directory('kempen-images')->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    //Search & Read (Papar Data & cari)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_kempen')->label('')->circular()->size(40),
                Tables\Columns\TextColumn::make('tajuk_kempen')->label('Tajuk')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('sasaran_dana')->label('Sasaran')->money('MYR'),
                Tables\Columns\TextColumn::make('jumlah_kutipan_semasa')
                    ->label('Kutipan Kasar')
                    ->money('MYR')
                    ->description(fn (Kempen $record): string => 'Caj Platform (4%): RM ' . number_format($record->caj_platform, 2)),
                Tables\Columns\TextColumn::make('amaun_bersih_ngo')
                    ->label('Amaun Bersih')
                    ->money('MYR')
                    ->weight('bold')
                    ->color('success'),
                Tables\Columns\TextColumn::make('peratus_kutipan')
                    ->label('%')
                    ->getStateUsing(fn (Kempen $record) => $record->peratus_kutipan . '%'),
                Tables\Columns\BadgeColumn::make('status_kempen')->label('Status')
                    ->colors(['warning' => 'Pending', 'success' => 'Aktif', 'danger' => 'Ditolak', 'info' => 'Selesai', 'primary' => 'Dibayar']),
                Tables\Columns\TextColumn::make('tarikh_tamat')->label('Tamat')->date('d/m/Y'),
            ])
            ->filters([
                // Filter: Status kempen
                Tables\Filters\SelectFilter::make('status_kempen')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Aktif'   => 'Aktif',
                        'Ditolak' => 'Ditolak',
                        'Selesai' => 'Selesai',
                        'Dibayar' => 'Dibayar',
                    ]),

                // Filter: Kategori
                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'Pendidikan'   => 'Pendidikan',
                        'Kesihatan'    => 'Kesihatan',
                        'Bencana Alam' => 'Bencana Alam',
                        'Anak Yatim'   => 'Anak Yatim',
                        'Fakir Miskin' => 'Fakir Miskin',
                        'Lain-lain'    => 'Lain-lain',
                    ]),

                // Filter: Kempen diarkibkan
                Tables\Filters\TrashedFilter::make()->label('Rekod Diarkibkan'),
            ])
            ->actions([
                Tables\Actions\Action::make('minta_bayaran')
                    ->label('Minta Bayaran')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (Kempen $record) => $record->status_kempen === 'Aktif' && !$record->bayaran_diminta)
                    ->requiresConfirmation()
                    ->modalDescription('Adakah anda pasti ingin meminta bayaran dana untuk kempen ini?')
                    ->action(function (Kempen $record) {
                        $record->update(['bayaran_diminta' => true, 'tarikh_minta_bayaran' => now()]);
                        Notification::make()->title('Permohonan bayaran dihantar!')->success()->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Kempen $record) => $record->status_kempen === 'Pending'),
                Tables\Actions\ViewAction::make(),

                //  Padam Selamat (hanya Pending, tiada derma) 
                Tables\Actions\DeleteAction::make()
                    ->label('Tarik Balik Kempen')
                    ->modalHeading('Tarik Balik Kempen')
                    ->modalDescription(
                        'Kempen akan diarkibkan dan tidak dipapar kepada penderma. '
                        . 'Hanya kempen berstatus Pending yang boleh ditarik balik. '
                        . 'Data tidak dipadam secara kekal.'
                    )
                    ->visible(fn (Kempen $record) => $record->status_kempen === 'Pending' && !$record->trashed())
                    ->before(function (Kempen $record, Tables\Actions\DeleteAction $action) {
                        if ($record->status_kempen !== 'Pending') {
                            Notification::make()
                                ->title('Tidak dibenarkan!')
                                ->body('Hanya kempen berstatus Pending sahaja yang boleh ditarik balik.')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),

                //  Pulihkan kempen yang diarkibkan 
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->color('success')
                    ->visible(fn (Kempen $record) => $record->trashed()),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $organisasi = auth()->user()->organisasi;
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('id_organisasi', $organisasi?->id_organisasi);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKempenOrganisasi::route('/'),
            'create' => Pages\CreateKempenOrganisasi::route('/create'),
            'edit'   => Pages\EditKempenOrganisasi::route('/{record}/edit'),
            'view'   => Pages\ViewKempenOrganisasi::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            UpdatesRelationManager::class,
        ];
    }


}
