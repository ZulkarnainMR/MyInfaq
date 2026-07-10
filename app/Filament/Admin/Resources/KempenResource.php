<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KempenResource\Pages;
use App\Models\Kempen;
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\KempenResource\RelationManagers\UpdatesRelationManager;

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
                        ->maxLength(255)
                        ->helperText('Masukkan nama atau tajuk kempen yang jelas dan ringkas.')
                        ->validationMessages([
                            'required' => 'Sila masukkan tajuk kempen.',
                        ]),
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
                        ->rows(4)
                        ->helperText('Berikan penjelasan terperinci mengenai tujuan kempen ini.')
                        ->validationMessages([
                            'required' => 'Sila masukkan keterangan kempen.',
                        ]),
                    Forms\Components\TextInput::make('sasaran_dana')
                        ->label('Sasaran Dana (RM)')
                        ->numeric()
                        ->required()
                        ->prefix('RM')
                        ->helperText('Nyatakan jumlah sasaran sumbangan yang diperlukan.')
                        ->hintIcon('heroicon-m-information-circle', tooltip: 'Jumlah ini akan dipaparkan kepada umum')
                        ->validationMessages([
                            'required' => 'Sila masukkan jumlah sasaran dana untuk kempen ini.',
                            'numeric' => 'Sila pastikan anda memasukkan nombor sahaja tanpa simbol atau huruf.',
                        ]),
                    Forms\Components\DatePicker::make('tarikh_tamat')
                        ->label('Tarikh Tamat'),
                    Forms\Components\FileUpload::make('gambar_kempen')
                        ->label('Gambar Kempen')
                        ->image()
                        ->directory('kempen-images')
                        ->helperText('Muat naik gambar beresolusi tinggi. Format disokong: JPG, PNG.'),
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
                        ->visible(fn ($get) => $get('status_kempen') === 'Ditolak')
                        ->required(fn ($get) => $get('status_kempen') === 'Ditolak')
                        ->helperText('Nyatakan alasan kenapa kempen ini ditolak untuk rujukan pemohon.')
                        ->validationMessages([
                            'required' => 'Alasan penolakan WAJIB diisi jika anda menolak kempen ini.',
                        ]),
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
                // Filter: Status kempen
                Tables\Filters\SelectFilter::make('status_kempen')
                    ->label('Status')
                    ->options([
                        'Pending'  => 'Pending',
                        'Aktif'    => 'Aktif',
                        'Ditolak'  => 'Ditolak',
                        'Selesai'  => 'Selesai',
                        'Dibayar'  => 'Dibayar',
                    ]),

                // Filter: Kategori kempen
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

                // Filter: Kempen yang dipadam (SoftDeletes)
                Tables\Filters\TrashedFilter::make()
                    ->label('Rekod Terpadam'),

                // Filter: Tarikh cipta (julat)
                Tables\Filters\Filter::make('tarikh_cipta')
                    ->label('Tarikh Daftar')
                    ->form([
                        Forms\Components\DatePicker::make('dari')->label('Dari'),
                        Forms\Components\DatePicker::make('hingga')->label('Hingga'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari'],   fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
                            ->when($data['hingga'], fn ($q, $v) => $q->whereDate('created_at', '<=', $v));
                    }),
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

                // ── Padam Selamat (Soft Delete) ──────────────────────────────────────
                // Hanya kempen tanpa derma Berjaya yang boleh dipadam.
                // Kempen yang ada derma Berjaya TIDAK boleh dipadam — hanya admin boleh force delete.
                Tables\Actions\DeleteAction::make()
                    ->label('Padam')
                    ->modalHeading('Padam Kempen (Soft Delete)')
                    ->modalDescription(
                        'Kempen akan diarkibkan dan TIDAK dipadam selama-lamanya. '
                        . 'Data masih boleh dipulihkan. Kempen yang mempunyai derma Berjaya '
                        . 'TIDAK boleh dipadam untuk melindungi rekod kewangan.'
                    )
                    ->before(function (Kempen $record, Tables\Actions\DeleteAction $action) {
                        // Halang padam jika ada derma Berjaya
                        $adaDerma = $record->derma()->where('status_bayaran', 'Berjaya')->exists();
                        if ($adaDerma) {
                            Notification::make()
                                ->title('Tidak boleh dipadam!')
                                ->body('Kempen ini mempunyai rekod derma Berjaya. Data kewangan tidak boleh dipadam. Tukar status kepada \'Selesai\' sebaliknya.')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),

                // ── Pulih Rekod (Restore dari trash) ────────────────────────────────
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->color('success'),

                // ── Padam Kekal (Force Delete) — Admin Sahaja ────────────────────────
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Padam Kekal')
                    ->modalHeading('⚠️ Padam Kekal — Tidak Boleh Diundur!')
                    ->modalDescription(
                        'Tindakan ini akan memadamkan kempen secara KEKAL dari pangkalan data. '
                        . 'Ini hanya perlu dilakukan jika kempen adalah entri ujian atau salah. '
                        . 'Data kewangan (derma) yang berkaitan TIDAK boleh dipadam dengan cara ini.'
                    )
                    ->before(function (Kempen $record, Tables\Actions\ForceDeleteAction $action) {
                        $adaDerma = $record->derma()->where('status_bayaran', 'Berjaya')->exists();
                        if ($adaDerma) {
                            Notification::make()
                                ->title('Dihalang!')
                                ->body('Kempen ini mempunyai rekod derma Berjaya. Padam kekal tidak dibenarkan.')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Bulk soft delete — dengan pengesahan
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Arkibkan Yang Dipilih')
                        ->modalHeading('Arkibkan Kempen Terpilih')
                        ->modalDescription('Kempen yang dipilih akan diarkibkan (soft delete). Kempen yang ada derma Berjaya tidak akan terjejas.'),
                    // Bulk restore
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Yang Dipilih'),
                    // Bulk force delete — hati-hati
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Padam Kekal Yang Dipilih'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Sertakan rekod yang dipadam (trashed) supaya admin boleh lihat & pulihkan
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
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
