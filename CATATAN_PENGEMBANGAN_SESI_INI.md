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
* **Halaman Beranda Utama (`/`):** Dikembalikan ke desain visual asli dengan Hero banner sampul visual, kartu aksi asesmen, galeri carousel kegiatan, dan seksi **Peta Interaktif Presisi** yang disematkan elegan di bagian tengah.
* **Halaman Beranda Alternatif (`/landing-v2`):** Mengadopsi gaya editorial minimalis *World Population Review* dengan *sticky sidebar navigation*, kartu perbandingan skor tertinggi vs rata-rata wilayah, dan seksi khusus **Definisi & Metodologi** berdasarkan dokumen panduan BBPSDM Komdigi Makassar.
* **Halaman Leaderboard:**
  * Leaderboard Klasik: `/ranking-sekolah`
  * Leaderboard Rincian Komposit V2: `/ranking-v2` (menampilkan rincian nilai asesmen 70%, nilai survei siswa 30%, jumlah responden, dan badge tier).

---

## 📁 Berkas Baru & Modifikasi Utama

* `app/Http/Controllers/StudentSurveyController.php` *(Baru)*: Logika pengisian survei siswa dan kalkulasi skor Likert.
* `app/Models/School.php` *(Modifikasi)*: Relasi respon siswa, atribut predikat tier, dan method `recalculateOverallScore()`.
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
