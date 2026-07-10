<?php

namespace App\Filament\Admin\Resources\KempenResource\Pages;

use App\Filament\Admin\Resources\KempenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKempen extends EditRecord
{
    protected static string $resource = KempenResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
