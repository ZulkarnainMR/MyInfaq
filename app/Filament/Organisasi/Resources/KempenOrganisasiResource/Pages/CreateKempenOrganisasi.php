<?php

namespace App\Filament\Organisasi\Resources\KempenOrganisasiResource\Pages;

use App\Filament\Organisasi\Resources\KempenOrganisasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKempenOrganisasi extends CreateRecord
{
    protected static string $resource = KempenOrganisasiResource::class;
    protected static ?string $title = 'Daftar Kempen Baharu';
    protected static ?string $breadcrumb = 'Daftar';
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Tetapkan ID Organisasi secara automatik
        $data['id_organisasi'] = auth()->user()->organisasi->id_organisasi;
        
        // Tetapkan status kempen secara lalai (default)
        $data['status_kempen'] = 'Pending';
        
        return $data;
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Simpan');
    }

    protected function getCreateAnotherFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Simpan & Cipta Baharu');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
