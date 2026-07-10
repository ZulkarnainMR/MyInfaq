<?php

namespace App\Filament\Organisasi\Resources\KempenOrganisasiResource\Pages;

use App\Filament\Organisasi\Resources\KempenOrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKempenOrganisasi extends ListRecords
{
    protected static string $resource = KempenOrganisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Cipta Kempen Baru'),
        ];
    }
}
