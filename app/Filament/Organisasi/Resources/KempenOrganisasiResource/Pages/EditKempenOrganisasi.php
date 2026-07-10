<?php

namespace App\Filament\Organisasi\Resources\KempenOrganisasiResource\Pages;

use App\Filament\Organisasi\Resources\KempenOrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKempenOrganisasi extends EditRecord
{
    protected static string $resource = KempenOrganisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
