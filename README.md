<p align="center">
  <a href="http://antigravity_sandbox.test/handal/public/">
    <img src="public/img/Handal 8.png" alt="Logo Handal Sekolah Aman Digital" width="500">
  </a>
</p>

<h1 align="center">HANDAL — Sekolah Aman Digital</h1>

<p align="center">
  <strong>Platform Pemeringkatan & Evaluasi Komposit Maturitas Keamanan Siber serta Literasi Digital Sekolah Menengah</strong>
</p>

<p align="center">
  Inisiatif Balai Besar Pengembangan SDM dan Penelitian Komunikasi dan Digital (BBLSDM Komdigi) Makassar<br>
  Kementerian Komunikasi dan Digital Republik Indonesia
</p>

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  </a>
  <a href="https://tailwindcss.com" target="_blank">
    <img src="https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 4.0">
  </a>
  <a href="https://leafletjs.com" target="_blank">
    <img src="https://img.shields.io/badge/Leaflet_GIS-1.9.4-199900?style=for-the-badge&logo=leaflet&logoColor=white" alt="Leaflet GIS">
  </a>
</p>

---

## 📌 Tentang Program HANDAL

**HANDAL (Sekolah Aman Digital)** adalah platform berbasis web resmi yang dirancang untuk memetakan, mengukur, dan meningkatkan maturitas keamanan siber serta literasi digital pada institusi pendidikan tingkat menengah (SMA/SMK/MA) di Indonesia.

Program ini mengadopsi kerangka kerja *Digital Literacy Global Framework (DLGF) UNESCO* dan standar *International Telecommunication Union (ITU)* guna memastikan terciptanya ekosistem digital sekolah yang aman, beretika, dan terlindungi dari ancaman siber seperti *cyberbullying*, peretasan, pencurian data, dan doxing.

---

## 🚀 Fitur Unggulan Sistem

### 1. Sistem Skoring Komposit Terotomatisasi (70% - 30%)
Sistem ini menggunakan formula evaluasi komposit yang adil dan objektif:
$$\text{Skor Akhir} = (0.70 \times \text{Skor Asesmen Mandiri}) + (0.30 \times \text{Rata-rata Survei Siswa})$$

* **70% Asesmen Mandiri Sekolah:** Diisi oleh admin/staf IT sekolah dan diverifikasi berkas buktinya oleh tim validator Kemkomdigi.
* **30% Kuesioner Persepsi Siswa:** Diisi secara anonim oleh sampel siswa melalui tautan unik/QR Code per sekolah dengan 8 butir pertanyaan skala Likert (1-4). Pertanyaan diacak urutannya (*randomized*) untuk mencegah kecurangan.

### 2. Peta Sebaran Spasial Presisi (100% Free & Bebas API Key)
* **Vektor Wilayah Resmi (GeoJSON):** Mengintegrasikan data batas administratif 24 Kabupaten/Kota di Provinsi Sulawesi Selatan (tanpa mencampur Sulawesi Barat) dan batas 38 provinsi seluruh Nusantara.
* **Koordinat GPS Sekolah Presisi:** Titik lokasi sekolah dipetakan akurat sesuai data Kemendikdasmen dan Google Maps (Kecamatan Bontoala, Tamalate, Tamalanrea, dll.).
* **Kontrol Navigasi Cepat:** Tombol zoom instan ke *Kota Makassar*, *Provinsi Sulawesi Selatan*, dan *Seluruh Nusantara*.

### 3. Klasifikasi Predikat Kesiapan Digital (Tier)
* 💎 **Unggul Digital (86 - 100):** Sekolah percontohan tata kelola siber matang.
* 🏆 **Maju (76 - 85):** Infrastruktur dan SDM siap, kebijakan berjalan aktif.
* 🎖️ **Berkembang (51 - 75):** Memiliki perangkat dasar, butuh penguatan SOP dan literasi.
* ⭐ **Pemula (31 - 50):** Memerlukan pendampingan intensif dari dinas terkait.
* ⚠️ **Rintisan (< 31):** Prioritas intervensi fasilitas dan pelatihan.

### 4. Pilihan Antarmuka Halaman
* **Beranda Utama (`/`):** Tampilan visual interaktif dengan Hero banner, kartu aksi asesmen, peta sebaran terintegrasi, dan galeri dokumentasi kegiatan.
* **Beranda Alternatif WPR (`/landing-v2`):** Tampilan bergaya editorial terinspirasi dari *World Population Review*, dilengkapi *sticky sidebar navigation*, kartu perbandingan (*Highest vs Baseline*), dan rincian dokumen metodologi.
* **Leaderboard (`/ranking-sekolah` & `/ranking-v2`):** Peringkat dinamis dengan rincian perolehan nilai sekolah secara transparan.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

* **Backend:** PHP 8.2+ / [Laravel 12](https://laravel.com)
* **Frontend:** Blade Templating, [Tailwind CSS v4](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
* **GIS & Pemetaan:** [Leaflet.js](https://leafletjs.com), Esri Light Gray Canvas, OpenStreetMap, GeoJSON Administrative Boundaries
* **Database:** MySQL / MariaDB (Didukung penuh di lingkungan Laragon)

---

## 💻 Panduan Instalasi & Menjalankan Proyek

### 1. Clone Repository
```bash
git clone https://github.com/MuhAndar/Handal.git
cd Handal
```

### 2. Install Dependensi Backend & Frontend
```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment
Salin berkas `.env.example` ke `.env` dan sesuaikan kredensial database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

Contoh konfigurasi database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=handal
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Data
Jalankan migrasi untuk membuat tabel pengguna, sekolah, kategori survei, dan soal survei siswa:
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi
Jika menggunakan **Laragon**, aplikasi dapat langsung diakses melalui virtual host lokal:
```
http://antigravity_sandbox.test/handal/public/
```
Atau menggunakan server bawaan artisan:
```bash
php artisan serve
```

---

## 👥 Akun Pengguna Bawaan (Default Seeders)

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@handal.com` | `12345678` | Kelola seluruh data sekolah dan verifikasi |
| **Validator** | `validator@handal.com` | `12345678` | Validasi berkas bukti asesmen mandiri |
| **Sekolah 1** | `school@handal.com` | `12345678` | SMAN 1 Makassar (Kode Survei: `sman1mks`) |
| **Sekolah 2** | `smkn2@handal.com` | `12345678` | SMKN 2 Makassar (Kode Survei: `smkn2mks`) |
| **Sekolah 3** | `man2@handal.com` | `12345678` | MAN 2 Model Makassar (Kode Survei: `man2mks`) |

---

## 🏛️ Kredit & Pembimbing

Proyek ini dikembangkan dalam naungan **Balai Besar Pengembangan SDM dan Penelitian Komunikasi dan Digital (BBLSDM Komdigi) Makassar**.

* **Pembimbing:** Muh. Andar Sugianto, S.T.
* **Tim Pengembang:**
  * Muhammad Zuhdi
  * Muhammad Naufal. N
  * Ainul Hayat. H
  * Muh. Sugandi

---

<p align="center">
  &copy; 2026 HANDAL Sekolah Aman Digital — BBLSDM Komdigi Makassar
</p>
