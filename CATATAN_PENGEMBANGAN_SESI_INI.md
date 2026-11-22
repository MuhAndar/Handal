# Catatan Ringkasan Pengembangan Sesi Ini (HANDAL)

> [!IMPORTANT]
> **PEMBERITAHUAN (TEMPORARY NOTE):**
> Berkas ini dibuat khusus untuk merangkum seluruh pekerjaan yang dikerjakan pada sesi ini. Sesuai instruksi, berkas ini dapat langsung Anda hapus (`git rm CATATAN_PENGEMBANGAN_SESI_INI.md`) saat melakukan *commit* / *push* pembaruan berikutnya dari perangkat lain.

---

## 📅 Tanggal Pengerjaan: November 2026
**Target Repositori Baru:** `https://github.com/MuhAndar/Handal.git`  
**Pembimbing:** Muh. Andar Sugianto, S.T.

---

## 🛠️ Rincian Fitur & Pembaruan yang Diselesaikan

### 1. Sistem Survei Siswa & Skoring Komposit (70% - 30%)
* **Latar Belakang:** Sebelumnya sistem hanya mengandalkan asesmen mandiri (100%), sementara survei siswa masih dilakukan secara manual di atas kertas lalu dimasukkan ke Google Spreadsheet.
* **Solusi yang Diterapkan:**
  1. Dibuat tabel database baru: `student_surveys` beserta model `StudentSurveyQuestion`, `StudentSurveyResponse`, dan `StudentSurveyAnswer`.
  2. Disediakan seeder kuesioner skala Likert (1 - 4) yang diambil langsung dari dokumen resmi panduan HANDAL.
  3. Dibuat pengontrol `StudentSurveyController` dengan fitur **pengacakan soal (*randomized order*)** otomatis bagi setiap siswa untuk mencegah kecurangan/contek jawaban.
  4. Setiap sekolah memiliki kode unik dan tautan langsung (misal: `/survey-siswa/sman1mks`) yang dapat diakses siswa via peramban HP/laptop atau kode QR.
  5. Ditambahkan fitur buka/tutup penerimaan survei siswa langsung dari Dashboard Sekolah.
  6. Skor akhir sekolah langsung dihitung ulang (*real-time*) dengan formula resmi:
     $$\text{Skor Akhir} = (0.70 \times \text{Asesmen Mandiri}) + (0.30 \times \text{Survei Siswa})$$

### 2. Peta Interaktif Bebas API Key & Batas Wilayah GeoJSON Presisi
* **Masalah Awal:** 
  1. Tampilan peta sebelumnya memunculkan watermark galat `API KEY REQUIRED carto.com/basemaps/apikey`.
  2. Masking poligon Sulawesi Selatan sebelumnya masih kasar dan keliru memasukkan wilayah Sulawesi Barat (Mamuju, Majene, dll.).
  3. Titik koordinat sekolah bertumpuk di satu titik kasar.
* **Perbaikan yang Dilakukan:**
  1. Mengganti basemap ke **Esri World Light Gray Canvas** dan **OpenStreetMap Standard** yang 100% gratis, bebas token/API key, serta berpenampilan bersih ala *World Population Review*.
  2. Mengintegrasikan berkas GeoJSON administratif resmi Indonesia (`public/data/sulsel_accurate.geojson`) yang memuat **24 Kabupaten/Kota definitif di Sulawesi Selatan** murni tanpa Sulawesi Barat, persis seperti garis batas di Google Maps.
  3. Mengunci titik koordinat GPS ketiga sekolah di Makassar sesuai data resmi Kemendikdasmen dan Google Maps:
     * **SMAN 1 Makassar:** `-5.1347, 119.4185` (Jl. Gunung Bawakaraeng No. 53, Kec. Bontoala)
     * **SMKN 2 Makassar:** `-5.1697, 119.4299` (Jl. Pancasila No. 15, Kec. Tamalate)
     * **MAN 2 Model Makassar:** `-5.1328, 119.4975` (Jl. Perintis Kemerdekaan KM. 12, Kec. Tamalanrea)
  4. Disediakan tombol navigasi cepat: **Kota Makassar** (zoom level 12), **Provinsi Sulsel** (*fit-bounds* seluruh 24 kab/kota), dan **Seluruh Nusantara**.

### 3. Penataan Antarmuka Halaman (Landing Page & Leaderboard)
* **Halaman Beranda Utama (`/`):**
  * Hero banner kini menampilkan **Logo Perisai HANDAL 3D Transparan** beresolusi tinggi (tanpa latar belakang hitam) dengan efek pendaran cahaya (*ambient glow*), menggantikan foto sampul sebelumnya di sebelah kanan.
  * Teks platform diperluas untuk mencakup **seluruh jenjang sekolah (SD, SMP, SMA/SMK/MA)**.
  * Dilengkapi floating menu interaktif di sisi kiri (Dock Navigasi) yang muncul saat pengguna menggulir ke bawah, menghubungkan langsung ke Peta Sebaran, Tabel Peringkat, Metodologi, dan Dasar Hukum.
  * Tabel pemeringkatan di beranda kini dilengkapi filter pencarian real-time dan tab jenjang (Semua, SD, SMP, SMA, SMK, MA).
* **Halaman Beranda Alternatif (`/landing-v2`):** Mengadopsi gaya editorial minimalis *World Population Review* dengan *sticky sidebar navigation*, kartu perbandingan skor tertinggi vs rata-rata wilayah, dan seksi khusus **Definisi & Metodologi** berdasarkan dokumen panduan BBLSDM Komdigi Makassar.
* **Halaman Leaderboard:**
  * Leaderboard Klasik: `/ranking-sekolah`
  * Leaderboard Rincian Komposit V2: `/ranking-v2` (menampilkan rincian nilai asesmen 70%, nilai survei siswa 30%, jumlah responden, dan badge tier).

### 4. Halaman Khusus Kompetisi Speed Coding Scratch (`/speed-coding`)
* **Latar Belakang:** Dibutuhkan halaman khusus yang mudah diakses dari header navbar untuk menampilkan riwayat dan pemenang lomba Speed Coding tahunan.
* **Fitur Utama:**
  1. **Aplikasi Standar:** Menggunakan aplikasi **Scratch 3.0** (pemrograman berbasis blok logika).
  2. **Jenjang Peserta:** Dikhususkan untuk siswa **SD (Kelas 5 - 6)** dan **SMP (Kelas 1 - 3 / Kelas 7 - 9)**.
  3. **Podium Juara 1, 2, 3 dengan Animasi Khusus:** Visual podium bertingkat 3-tier (*Juara 1 emas di tengah paling tinggi dengan mahkota berdenyut*, Juara 2 perak di kiri, Juara 3 perunggu di kanan) lengkap dengan nama, sekolah, kelas, catatan waktu, dan skor akurasi.
  4. **Tombol Pengganti Tahun (Annual Switcher):** Berpindah instan antara **Edisi 2026 (Tahun Ini)** dan **Edisi 2025 (Tahun Lalu)**.
  5. **Tabel Interaktif 30 Peserta:** Pencarian instan dan tab filter (Semua 30 Peserta, Top 10 Finalis, Podium Juara, SD, dan SMP).
  6. **Branding:** Menampilkan **Logo Resmi BBLSDM Komdigi Makassar** pada header kejuaraan.

### 5. Standardisasi Instansi BBLSDM & Transisi Halus Antar Menu
* **Perubahan Nomenklatur:** Seluruh teks "BBPSDM Komdigi Makassar" di seluruh berkas dan dokumen proyek resmi diubah menjadi **"BBLSDM Komdigi Makassar"**.
* **Pengubahan Istilah:** Istilah "Lomba Speed Coding" di navbar dan judul halaman diubah menjadi **"Kompetisi Speed Coding"**.
* **Transisi Halus (*Smooth Transitions*):**
  * `scroll-behavior: smooth` aktif untuk pergerakan navigasi antar seksi di halaman beranda.
  * Transisi *page fading* halus saat berpindah antar rute internal website.

---

## 📁 Berkas Baru & Modifikasi Utama

* `app/Http/Controllers/SpeedCodingController.php` *(Baru)*: Pengontrol data 30 peserta, podium juara, dan riwayat edisi kompetisi Speed Coding Scratch.
* `resources/views/pages/speed_coding.blade.php` *(Baru)*: Tampilan halaman kejuaraan Speed Coding dengan tema cyber-mode, podium animasi, dan tabel interaktif.
* `public/img/logo_handal_shield_transparent.png` *(Baru)*: Logo 3D shield HANDAL dengan latar belakang transparan berkualitas tinggi.
* `public/img/logo_bblsdm_komdigi.png` *(Baru)*: Logo resmi BBLSDM Komdigi Makassar transparan.
* `app/Http/Controllers/StudentSurveyController.php` *(Baru)*: Logika pengisian survei siswa dan kalkulasi skor Likert.
* `app/Models/School.php` *(Modifikasi)*: Relasi respon siswa, atribut predikat tier, dan method `recalculateOverallScore()`.
* `public/data/sulsel_accurate.geojson` *(Baru)*: Data batas administratif 24 Kab/Kota Sulsel.
* `resources/views/layouts/app.blade.php` *(Modifikasi)*: Penambahan menu Kompetisi Speed Coding, transisi smooth, dan penyesuaian footer BBLSDM.
* `resources/views/landing.blade.php` *(Modifikasi)*: Pemasangan logo besar transparan di kanan, perbaikan kode bocor, dock navigasi kiri, dan peta Leaflet.
* `app/Models/StudentSurveyQuestion.php`, `StudentSurveyResponse.php`, `StudentSurveyAnswer.php` *(Baru)*.
* `database/migrations/2026_11_02_055406_create_student_surveys_table.php` *(Baru)*.
* `database/seeders/StudentSurveyQuestionSeeder.php` *(Baru)*.
* `public/data/sulsel_accurate.geojson` *(Baru)*: Data batas administratif 24 Kab/Kota Sulsel.
* `public/data/indonesia_provinces.geojson` *(Baru)*: Data batas 38 provinsi di Indonesia.
* `resources/views/landing.blade.php` *(Modifikasi)*: Penambahan seksi peta Leaflet terintegrasi pada desain klasik.
* `resources/views/landing_v2.blade.php` *(Baru)*: Beranda ala World Population Review.
* `resources/views/pages/ranking_v2.blade.php` *(Baru)*: Leaderboard rincian 70-30%.
* `resources/views/student_survey/show.blade.php`, `closed.blade.php`, `thank_you.blade.php` *(Baru)*: Antarmuka kuesioner siswa.
* `README.md` *(Modifikasi)*: Dokumentasi lengkap proyek dalam bahasa Indonesia.

---
*Catatan: Dokumen ini siap dihapus pada commit selanjutnya di perangkat lain.*
