# MyInfaq — Sistem Pengurusan Derma & Kempen Islamik

Sistem pengurusan derma (infaq) berasaskan web yang membolehkan pentadbir mencipta
dan mengurus kempen derma, serta orang ramai membuat sumbangan secara dalam talian
melalui integrasi pembayaran ToyyibPay.

## Tech Stack
- **Backend:** Laravel + Filament (admin panel/dashboard)
- **Database:** MySQL
- **Payment Gateway:** ToyyibPay
- **Dev tunnel:** ngrok (untuk testing webhook payment secara local)

## Ciri-ciri Utama
- Pengurusan kempen derma (create, update, track progress kutipan)
- Integrasi pembayaran ToyyibPay (proses transaksi derma secara online)
- Dashboard admin (Filament) — widget statistik kutipan
- Laporan (SQL JOIN reports) untuk analisis derma ikut kempen/tempoh
- Error handling (try-catch) untuk transaksi pembayaran
- Soft deletes untuk rekod kempen (elak kehilangan data secara tidak sengaja)

## Reka Bentuk Sistem
- Data Flow Diagram (DFD) & Entity Relationship Diagram (ERD) — lihat fail
  `DFD_Level1_MyInfaq.md` dan `Rajah_Konteks_MyInfaq.md`
- ToyyibPay dimodelkan sebagai external entity dalam DFD

## Screenshot
<!-- letak 2-3 screenshot UI di sini, guna Light Mode -->

## Cara Setup (Local Development)
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

## Status Projek
Final Year Project (FYP) — Diploma Sains Komputer, UniSZA.

## Pembangun
Muhammad Zulkarnain Bin Mohd Rozi — [LinkedIn](#) | [Portfolio](#)
