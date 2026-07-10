<?php

namespace App\Filament\Admin\Resources\KeteluasanResource\Pages;

use App\Filament\Admin\Resources\KetelusanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKetelusan extends EditRecord
{
    protected static string $resource = KetelusanResource::class;
    protected function getHeaderActions(): array { return [Actions\DeleteAction::make()]; }
}
