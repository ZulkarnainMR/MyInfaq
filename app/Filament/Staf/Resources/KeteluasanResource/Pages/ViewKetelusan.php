<?php

namespace App\Filament\Staf\Resources\KeteluasanResource\Pages;

use App\Filament\Staf\Resources\KetelusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKetelusan extends ViewRecord
{
    protected static string $resource = KetelusanResource::class;
    protected function getHeaderActions(): array { return [Actions\EditAction::make()]; }
}
