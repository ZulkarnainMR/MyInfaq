<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Organisasi;
use App\Models\Penderma;
use App\Models\Staf;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    // Simpan sementara data profil yang disedut dari form
    protected array $stafData       = [];
    protected array $pendermaData   = [];
    protected array $organisasiData = [];

    /**
     * Dipanggil SEBELUM User disimpan ke tbl_users.
     * Sedut keluar semua field profil supaya ia tidak
     * cuba disimpan ke tbl_users (kolum tidak wujud di sana).
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Sedut data Staf
        $this->stafData = [
            'nama_staf' => $data['nama_staf'] ?? null,
            'jawatan'   => $data['jawatan'] ?? null,
        ];

        // Sedut data Penderma
        $this->pendermaData = [
            'nama_penderma' => $data['nama_penderma'] ?? null,
            'no_telefon'    => $data['no_telefon'] ?? null,
        ];

        // Sedut data Organisasi
        $this->organisasiData = [
            'nama_organisasi' => $data['nama_organisasi'] ?? null,
            'no_pendaftaran'  => $data['no_pendaftaran'] ?? null,
            'no_telefon'      => $data['no_telefon_org'] ?? null,
            'alamat'          => $data['alamat'] ?? null,
        ];

        // Buang semua field profil dari data user
        unset(
            $data['nama_staf'],
            $data['jawatan'],
            $data['nama_penderma'],
            $data['no_telefon'],
            $data['nama_organisasi'],
            $data['no_pendaftaran'],
            $data['no_telefon_org'],
            $data['alamat'],
        );

        return $data;
    }

    /**
     * Dipanggil SELEPAS User berjaya disimpan ke tbl_users.
     * Cipta rekod profil dalam jadual berkaitan mengikut role.
     */
    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->role === 'Staf') {
            Staf::create([
                'id_user'   => $record->id_user,
                'nama_staf' => $this->stafData['nama_staf'] ?? 'Staf Baru',
                'jawatan'   => $this->stafData['jawatan'] ?? null,
            ]);
        }

        if ($record->role === 'Penderma') {
            Penderma::create([
                'id_user'       => $record->id_user,
                'nama_penderma' => $this->pendermaData['nama_penderma'] ?? 'Penderma Baru',
                'no_telefon'    => $this->pendermaData['no_telefon'] ?? null,
            ]);
        }

        if ($record->role === 'Organisasi') {
            Organisasi::create([
                'id_user'         => $record->id_user,
                'nama_organisasi' => $this->organisasiData['nama_organisasi'] ?? 'Organisasi Baru',
                'no_pendaftaran'  => $this->organisasiData['no_pendaftaran'] ?? '',
                'no_telefon'      => $this->organisasiData['no_telefon'] ?? '',
                'alamat'          => $this->organisasiData['alamat'] ?? null,
            ]);
        }
    }
}
