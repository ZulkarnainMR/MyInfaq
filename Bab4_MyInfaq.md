### 4.2 Rajah Konteks (Context Diagram)

Rajah Konteks merupakan aliran data paras tertinggi (Level 0) yang menunjukkan skop dan sempadan bagi sesebuah sistem. Rajah ini dilakar pada peringkat permulaan apabila ingin menyediakan Rajah Aliran Data (DFD) bagi sesebuah persekitaran sistem. Rajah Konteks ini bertujuan menerangkan interaksi dan hubungan aliran data di antara satu proses utama (Sistem MyInfaq) dengan entiti-entiti luaran yang lain. 

Memandangkan Sistem MyInfaq ini berkonsepkan platform berpusat (di mana pelbagai organisasi boleh mendaftar), Rajah Konteks bagi sistem ini menyenaraikan empat (4) entiti utama yang terlibat iaitu:

**i. Penderma (Orang Awam)**
Penderma merupakan entiti utama yang terlibat dalam sistem ini kerana penderma akan mengakses sistem untuk melihat senarai kempen amal, mendaftar akaun log masuk, melakukan transaksi sumbangan secara atas talian ke tabung pilihan mereka, dan menerima resit rasmi secara digital.

**ii. Pihak Organisasi (Masjid / Surau / NGO)**
Pihak organisasi merupakan entiti yang mendaftar ke dalam platform MyInfaq untuk memulakan dan menguruskan kempen kutipan dana mereka. Entiti ini berinteraksi dengan sistem dengan cara memasukkan butiran profil organisasi, mencipta kempen infaq baharu, serta menyemak jumlah dana dan laporan penderma khusus untuk tabung mereka.

**iii. Pentadbir Sistem (Super Admin)**
Pentadbir Sistem merujuk kepada pemilik atau pengurus keseluruhan platform MyInfaq. Entiti ini bertanggungjawab untuk menyemak dan meluluskan pendaftaran entiti "Pihak Organisasi" yang baharu (bagi mengelakkan penipuan scammer), memantau keseluruhan aliran transaksi dalam platform, serta menyelenggara fungsi-fungsi utama sistem.

**iv. Gerbang Pembayaran (toyyibPay)**
Gerbang pembayaran merupakan entiti sistem luaran (*external entity*) yang berhubung dengan sistem MyInfaq. Entiti ini berfungsi untuk menerima maklumat permintaan bayaran daripada penderma, memproses transaksi menerusi API toyyibPay, dan memulangkan data status bayaran (berjaya atau gagal) kembali kepada sistem MyInfaq untuk tujuan perekodan sistem dan janaan resit.
