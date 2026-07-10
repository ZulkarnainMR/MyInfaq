<?php

namespace App\Filament\Staf\Resources\KeteluasanResource\Pages;

use App\Filament\Staf\Resources\KetelusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKetelusan extends ListRecords
{
    protected static string $resource = KetelusanResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
