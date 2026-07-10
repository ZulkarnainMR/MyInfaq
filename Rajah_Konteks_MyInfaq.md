# Lakaran Rajah Konteks (DFD Tahap 0) - Sistem MyInfaq

Berikut adalah kod Mermaid untuk menghasilkan lakaran Rajah Konteks bagi sistem anda. Anda boleh letakkan kod ini di dalam mana-mana pemapar Markdown yang menyokong Mermaid untuk melihat rajahnya, atau jadikan rujukan semasa melukis di Microsoft Word/Draw.io.

```mermaid
flowchart TD
    %% Definisi style warna (bulatan untuk proses, petak untuk entiti)
    classDef process fill:#f9d0c4,stroke:#333,stroke-width:2px,color:#000
    classDef entity fill:#c4e1f9,stroke:#333,stroke-width:2px,color:#000

    %% Proses Utama (Bulatan)
    S(( 0 <br><br> Sistem <br> MyInfaq )):::process

    %% Entiti Luaran (Petak)
    E1[Penderma]:::entity
    E2[Organisasi]:::entity
    E3[Staf dan Admin]:::entity
    E4[Gerbang Pembayaran toyyibPay]:::entity

    %% Aliran Data: Penderma <--> Sistem
    E1 -- "Maklumat Pendaftaran &<br>Butiran Pembayaran" --> S
    S -- "Resit Digital &<br>Pengesahan Akaun" --> E1

    %% Aliran Data: Organisasi <--> Sistem
    E2 -- "Maklumat Profil Organisasi &<br>Butiran Kempen" --> S
    S -- "Status Kelulusan Pendaftaran &<br>Laporan Kutipan Tabung" --> E2

    %% Aliran Data: Staf dan Admin <--> Sistem
    E3 -- "Kelulusan Profil Organisasi &<br>Tetapan Sistem" --> S
    S -- "Laporan Kewangan Keseluruhan &<br>Log Transaksi" --> E3

    %% Aliran Data: Sistem <--> toyyibPay
    S -- "Data Permintaan Transaksi" --> E4
    E4 -- "Status Transaksi (Berjaya/Gagal)" --> S
```
