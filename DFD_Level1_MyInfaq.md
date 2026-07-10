# Lakaran Rajah Aliran Data Tahap 1 (DFD Level 1) - Sistem MyInfaq

Rajah DFD Tahap 1 berfungsi untuk "memecahkan" bulatan utama (Sistem MyInfaq) dalam Rajah Konteks kepada beberapa proses atau modul yang lebih kecil. Dalam DFD Tahap 1 juga, kita mula memperkenalkan **Pangkalan Data (Data Store)**.

Berikut adalah kod Mermaid untuk DFD Level 1 anda:

```mermaid
flowchart TD
    %% Definisi style warna
    classDef process fill:#f9d0c4,stroke:#333,stroke-width:2px,color:#000
    classDef entity fill:#c4e1f9,stroke:#333,stroke-width:2px,color:#000
    classDef datastore fill:#fffb8f,stroke:#333,stroke-width:2px,color:#000

    %% ---------------- ENTITI LUARAN ----------------
    E1[Penderma]:::entity
    E2[Organisasi]:::entity
    E3[Staf dan Admin]:::entity
    E4[toyyibPay]:::entity

    %% ---------------- PROSES UTAMA ----------------
    P1((1.0 <br> Urus Pendaftaran <br> & Log Masuk)):::process
    P2((2.0 <br> Urus Profil Organisasi <br> & Kempen Infaq)):::process
    P3((3.0 <br> Proses Sumbangan <br> & Transaksi FPX)):::process
    P4((4.0 <br> Penjanaan <br> Laporan Lanjut)):::process

    %% ---------------- PANGKALAN DATA (DATA STORE) ----------------
    D1[(D1: Jadual Pengguna)]:::datastore
    D2[(D2: Jadual Organisasi)]:::datastore
    D3[(D3: Jadual Kempen)]:::datastore
    D4[(D4: Jadual Transaksi)]:::datastore

    %% ================= ALIRAN ENTITI KE PROSES =================
    
    %% Proses 1.0 (Pendaftaran)
    E1 -- "Data Pendaftaran / Log Masuk" --> P1
    P1 -- "Status Akaun" --> E1
    E3 -- "Arah Urus Pengguna" --> P1

    %% Proses 2.0 (Kempen/Organisasi)
    E2 -- "Borang Daftar Organisasi & Kempen" --> P2
    P2 -- "Notifikasi Status Kelulusan" --> E2
    E3 -- "Semakan & Kelulusan" --> P2

    %% Proses 3.0 (Transaksi)
    E1 -- "Jumlah Sumbangan & Pilihan Tabung" --> P3
    P3 -- "Resit Digital" --> E1
    P3 -- "Hantar Permintaan Bayaran" --> E4
    E4 -- "Status FPX (Berjaya/Gagal)" --> P3

    %% Proses 4.0 (Laporan)
    P4 -- "Laporan Khusus Tabung" --> E2
    P4 -- "Laporan Keseluruhan Sistem" --> E3

    %% ================= ALIRAN PROSES KE DATA STORE =================
    
    %% Simpan data Pengguna
    P1 -- "Rekod Pengguna Baru" --> D1
    D1 -- "Sahkan Kata Laluan" --> P1

    %% Simpan data Organisasi & Kempen
    P2 -- "Simpan Maklumat Organisasi" --> D2
    P2 -- "Simpan Maklumat Kempen" --> D3
    
    %% Proses Bayaran ambil data kempen, dan simpan log transaksi
    D3 -- "Semak Status Tabung (Aktif/Tutup)" --> P3
    P3 -- "Simpan Rekod Berjaya" --> D4
    
    %% Proses Laporan kutip maklumat dari data store
    D4 -- "Data Jumlah Duit" --> P4
    D3 -- "Senarai Nama Tabung" --> P4
```
