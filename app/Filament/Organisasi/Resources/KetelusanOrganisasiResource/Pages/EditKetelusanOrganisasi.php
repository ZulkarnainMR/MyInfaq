<?php

namespace App\Filament\Organisasi\Resources\KetelusanOrganisasiResource\Pages;

use App\Filament\Organisasi\Resources\KetelusanOrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKetelusanOrganisasi extends EditRecord
{
    protected static string $resource = KetelusanOrganisasiResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
