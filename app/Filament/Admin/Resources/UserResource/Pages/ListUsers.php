<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array { 
        return [
            Actions\Action::make('tetapan_sistem')
                ->label('Tetapan Sistem')
                ->icon('heroicon-o-cog-8-tooth')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\TextInput::make('bayaran_pendaftaran_organisasi')
                        ->label('Bayaran Pendaftaran Organisasi (RM)')
                        ->numeric()
                        ->required()
                        ->prefix('RM')
                        ->default(\App\Models\Tetapan::dapatkan('bayaran_pendaftaran_organisasi', 100)),
                    \Filament\Forms\Components\TextInput::make('peratus_cas_platform')
                        ->label('Kadar Cas Platform (%)')
                        ->numeric()
                        ->required()
                        ->suffix('%')
                        ->default(\App\Models\Tetapan::dapatkan('peratus_cas_platform', 4)),
                ])
                ->action(function (array $data) {
                    \App\Models\Tetapan::updateOrCreate(
                        ['kunci' => 'bayaran_pendaftaran_organisasi'],
                        ['nilai' => $data['bayaran_pendaftaran_organisasi']]
                    );
                    \App\Models\Tetapan::updateOrCreate(
                        ['kunci' => 'peratus_cas_platform'],
                        ['nilai' => $data['peratus_cas_platform']]
                    );
                    \Filament\Notifications\Notification::make()
                        ->title('Tetapan berjaya dikemas kini!')
                        ->success()
                        ->send();
                }),
            Actions\CreateAction::make()->label('Pengguna Baru')
        ]; 
    }
}
