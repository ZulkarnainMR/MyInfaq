<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\Organisasi;
use App\Models\Penderma;
use App\Models\Staf;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Pengurusan Pengguna';
    protected static ?int $navigationSort = 4;

    // Only admins see this resource
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->role === 'Admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Akaun Pengguna')->schema([
                Forms\Components\TextInput::make('email')
                    ->email()->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('password')
                    ->password()->required(fn ($record) => $record === null)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->label('Kata Laluan'),
                Forms\Components\Select::make('role')
                    ->options(['Admin' => 'Admin', 'Staf' => 'Staf', 'Penderma' => 'Penderma', 'Organisasi' => 'Organisasi'])
                    ->required()
                    ->live(),
            ])->columns(2),

            Forms\Components\Section::make('Profil Staf')
                ->schema([
                    Forms\Components\TextInput::make('nama_staf')
                        ->label('Nama Staf')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('jawatan')
                        ->label('Jawatan')
                        ->nullable()
                        ->maxLength(100),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('role') === 'Staf'),

            Forms\Components\Section::make('Profil Penderma')
                ->schema([
                    Forms\Components\TextInput::make('nama_penderma')
                        ->label('Nama Penderma')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('no_telefon')
                        ->label('No. Telefon')
                        ->nullable()
                        ->maxLength(20),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('role') === 'Penderma'),

            Forms\Components\Section::make('Profil Organisasi')
                ->schema([
                    Forms\Components\TextInput::make('nama_organisasi')
                        ->label('Nama Organisasi')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('no_pendaftaran')
                        ->label('No. Pendaftaran')
                        ->required()
                        ->unique('tbl_organisasi', 'no_pendaftaran')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('no_telefon_org')
                        ->label('No. Telefon')
                        ->required()
                        ->maxLength(20),
                    Forms\Components\Textarea::make('alamat')
                        ->label('Alamat')
                        ->nullable()
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->visible(fn (Forms\Get $get) => $get('role') === 'Organisasi'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'danger'  => 'Admin',
                        'warning' => 'Staf',
                        'success' => 'Penderma',
                        'info'    => 'Organisasi',
                    ]),
                Tables\Columns\TextColumn::make('tarikh_daftar')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(['Admin' => 'Admin', 'Staf' => 'Staf', 'Penderma' => 'Penderma', 'Organisasi' => 'Organisasi']),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Kemaskini'), 
                Tables\Actions\DeleteAction::make()->label('Padam')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
