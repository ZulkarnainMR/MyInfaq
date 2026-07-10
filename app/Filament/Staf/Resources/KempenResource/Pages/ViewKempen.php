<?php

namespace App\Filament\Staf\Resources\KempenResource\Pages;

use App\Filament\Staf\Resources\KempenResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKempen extends ViewRecord
{
    protected static string $resource = KempenResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
