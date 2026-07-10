<?php

namespace App\Filament\Admin\Resources\KeteluasanResource\Pages;

use App\Filament\Admin\Resources\KetelusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKetelusan extends ListRecords
{
    protected static string $resource = KetelusanResource::class;
    protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; }
}
