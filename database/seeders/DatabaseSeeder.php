<?php

namespace Database\Seeders;

use App\Models\Derma;
use App\Models\Kempen;
use App\Models\Ketelusan;
use App\Models\Organisasi;
use App\Models\Penderma;
use App\Models\Staf;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────────
        User::create([
            'email'    => 'admin@myinfaq.my',
            'password' => Hash::make('password'),
            'role'     => 'Admin',
        ]);

        // ── Staff ─────────────────────────────────────────────────────────────
        $stafUser = User::create([
            'email'    => 'staf@myinfaq.my',
            'password' => Hash::make('password'),
            'role'     => 'Staf',
        ]);
        $staf = Staf::create([
            'id_user'   => $stafUser->id_user,
            'nama_staf' => 'Ahmad Faris',
            'jawatan'   => 'Pegawai Audit',
        ]);

        // ── Organisations ─────────────────────────────────────────────────────
        $orgUser1 = User::create([
            'email'    => 'pertiwi@myinfaq.my',
            'password' => Hash::make('password'),
            'role'     => 'Organisasi',
        ]);
        $org1 = Organisasi::create([
            'id_user'         => $orgUser1->id_user,
            'nama_organisasi' => 'Yayasan Pertiwi Bakti',
            'no_pendaftaran'  => 'PPM-001-05-20012020',
            'no_telefon'      => '03-12345678',
            'alamat'          => 'Jalan Tuanku Abdul Halim, 50480 Kuala Lumpur',
        ]);

        $orgUser2 = User::create([
            'email'    => 'amanah@myinfaq.my',
            'password' => Hash::make('password'),
            'role'     => 'Organisasi',
        ]);
        $org2 = Organisasi::create([
            'id_user'         => $orgUser2->id_user,
            'nama_organisasi' => 'Persatuan Amanah Sejahtera',
            'no_pendaftaran'  => 'PPM-002-08-14062021',
            'no_telefon'      => '04-98765432',
            'alamat'          => 'No. 12, Jalan Kebajikan, 10000 Georgetown, Pulau Pinang',
        ]);

        // ── Donors ────────────────────────────────────────────────────────────
        $donors = [];
        $donorData = [
            ['Siti Rahmah Binti Aziz', 'siti@gmail.com'],
            ['Muhammad Hafiz Ismail', 'hafiz@gmail.com'],
            ['Nurul Aina Binti Kamal', 'aina@gmail.com'],
            ['Zulkifli Bin Hassan', 'zulkifli@gmail.com'],
        ];
        foreach ($donorData as [$name, $email]) {
            $u = User::create(['email' => $email, 'password' => Hash::make('password'), 'role' => 'Penderma']);
            $donors[] = Penderma::create(['id_user' => $u->id_user, 'nama_penderma' => $name]);
        }

        // ── Campaigns ─────────────────────────────────────────────────────────
        $k1 = Kempen::create([
            'id_organisasi'         => $org1->id_organisasi,
            'tajuk_kempen'          => 'Tabung Banjir Kelantan 2024',
            'keterangan_kempen'     => 'Dana kecemasan untuk mangsa banjir di Kelantan. Sumbangan akan digunakan untuk bekalan makanan, pakaian, dan tempat tinggal sementara bagi lebih 500 keluarga yang terjejas.',
            'sasaran_dana'          => 50000,
            'jumlah_kutipan_semasa' => 32500,
            'status_kempen'         => 'Aktif',
            'tarikh_tamat'          => now()->addDays(30),
            'id_staf'               => $staf->id_staf,
            'tarikh_semakan'        => now()->subDays(2),
        ]);

        $k2 = Kempen::create([
            'id_organisasi'         => $org1->id_organisasi,
            'tajuk_kempen'          => 'Biasiswa Anak Yatim 2024',
            'keterangan_kempen'     => 'Program biasiswa untuk 50 orang anak yatim dari keluarga berpendapatan rendah bagi meneruskan pengajian peringkat menengah dan universiti.',
            'sasaran_dana'          => 30000,
            'jumlah_kutipan_semasa' => 18750,
            'status_kempen'         => 'Aktif',
            'tarikh_tamat'          => now()->addDays(60),
            'id_staf'               => $staf->id_staf,
            'tarikh_semakan'        => now()->subDays(5),
        ]);

        $k3 = Kempen::create([
            'id_organisasi'         => $org2->id_organisasi,
            'tajuk_kempen'          => 'Projek Telaga Air Bersih Sabah',
            'keterangan_kempen'     => 'Membina 10 telaga air bersih untuk komuniti pedalaman di Sabah yang masih tidak mempunyai akses kepada air bersih. Setiap telaga menelan kos RM 8,000.',
            'sasaran_dana'          => 80000,
            'jumlah_kutipan_semasa' => 80000,
            'status_kempen'         => 'Selesai',
            'tarikh_tamat'          => now()->subDays(10),
            'id_staf'               => $staf->id_staf,
            'tarikh_semakan'        => now()->subDays(90),
            'bayaran_diminta'       => true,
            'bayaran_diluluskan'    => true,
            'tarikh_bayaran_diluluskan' => now()->subDays(5),
        ]);

        $k4 = Kempen::create([
            'id_organisasi'     => $org2->id_organisasi,
            'tajuk_kempen'      => 'Surau Komuniti Kg. Baru Menggatal',
            'keterangan_kempen' => 'Pembinaan semula surau komuniti yang telah usang di Kg. Baru Menggatal, Kota Kinabalu.',
            'sasaran_dana'      => 45000,
            'status_kempen'     => 'Pending',
        ]);

        // ── Donations ─────────────────────────────────────────────────────────
        $donationData = [
            [$k1, $donors[0], 200], [$k1, $donors[1], 500], [$k1, $donors[2], 150],
            [$k1, $donors[3], 1000], [$k2, $donors[0], 300], [$k2, $donors[1], 250],
            [$k3, $donors[2], 5000], [$k3, $donors[3], 2000], [$k3, $donors[0], 1500],
        ];
        foreach ($donationData as [$kempen, $penderma, $amaun]) {
            Derma::create([
                'id_kempen'      => $kempen->id_kempen,
                'id_penderma'    => $penderma->id_penderma,
                'amaun_derma'    => $amaun,
                'status_bayaran' => 'Berjaya',
                'kaedah_bayaran' => 'FPX',
            ]);
        }

        // ── Ketelusan for completed campaign ──────────────────────────────────
        Ketelusan::create([
            'id_kempen'           => $k3->id_kempen,
            'tajuk_laporan'       => 'Penyerahan Telaga Fasa 1 – 5 Telaga',
            'keterangan_penerima' => 'Lima buah telaga berjaya disiapkan dan diserahkan kepada komuniti Kg. Limbuak, Kg. Pekan Nabalu, Kg. Tenghilan, Kg. Menggatal dan Kg. Tuaran. Seramai 850 penduduk kini mempunyai akses kepada air bersih.',
            'tarikh_agihan'       => now()->subDays(20),
            'bilangan_penerima'   => 850,
            'gambar_agihan'       => [],
            'status_audit'        => 'Diluluskan',
            'id_staf'             => $staf->id_staf,
            'tarikh_audit'        => now()->subDays(15),
        ]);

        Ketelusan::create([
            'id_kempen'           => $k3->id_kempen,
            'tajuk_laporan'       => 'Penyerahan Telaga Fasa 2 – 5 Telaga',
            'keterangan_penerima' => 'Lima telaga baki telah siap dan diserahkan kepada lima kampung lagi. Projek keseluruhan 10 telaga kini telah 100% selesai, memberi manfaat kepada lebih 1,600 penduduk.',
            'tarikh_agihan'       => now()->subDays(8),
            'bilangan_penerima'   => 760,
            'gambar_agihan'       => [],
            'status_audit'        => 'Diluluskan',
            'id_staf'             => $staf->id_staf,
            'tarikh_audit'        => now()->subDays(3),
        ]);
    }
}
