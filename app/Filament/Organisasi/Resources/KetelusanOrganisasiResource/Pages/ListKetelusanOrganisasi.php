<?php

namespace App\Filament\Organisasi\Resources\KetelusanOrganisasiResource\Pages;

use App\Filament\Organisasi\Resources\KetelusanOrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKetelusanOrganisasi extends ListRecords
{
    protected static string $resource = KetelusanOrganisasiResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()->label('Tambah Laporan')]; }
}
