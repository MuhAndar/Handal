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
* **Halaman Leaderboard Tunggal (`/ranking-sekolah`):**
  * Seluruh pemeringkatan sekolah kini distandardisasi menjadi **satu halaman tunggal** (`/ranking-sekolah`). Halaman terpisah v2 telah dihapus beserta seluruh tombol pengalih versi agar navigasi sistem bersih dan fokus.
  * > [!IMPORTANT]
  * > **AGENDA PENGEMBANGAN LANJUTAN (ROADMAP PENILAIAN):**
  * > Perlu memperbarui bobot penilaian sistem secara keseluruhan dan penilaian pada perangkingan (menyelaraskan skema formula skor asesmen mandiri, survei siswa, serta standardisasi distribusi bobot antar-indikator).

### 4. Halaman Khusus Kompetisi Speed Coding Scratch (`/speed-coding`)
* **Latar Belakang:** Halaman khusus untuk menampilkan riwayat kejuaraan dan pemenang lomba Speed Coding Scratch tahunan.
* **Fitur Utama & Pembaruan Terkini:**
  1. **Aplikasi Standar:** Menggunakan aplikasi **Scratch 3.0** (pemrograman berbasis blok logika).
  2. **Kategori Terbuka (SD & SMP Digabung):** Siswa SD (Kelas 5-6) dan SMP (Kelas 1-3) bertanding dalam satu kategori perlombaan terbuka tanpa pemisahan jenjang.
  3. **Pemisahan 2 Tahap (Babak Kualifikasi & Babak Final):** 
     - **Babak Kualifikasi:** Diikuti oleh seluruh 30 peserta dengan catatan waktu dan skor kualifikasi masing-masing.
     - **Babak Final:** Diikuti khusus 10 peserta terbaik (Top 10) yang lolos dengan soal tantangan Scratch babak baru, menghasilkan catatan waktu dan skor final yang berbeda.
     - Pemenang Juara 1, 2, dan 3 ditentukan langsung dari performa pada Babak Final.
  4. **Podium Juara 1, 2, 3 dengan Proporsi Rapi:** Jarak vertikal mahkota animasi `👑` kini diperlebar agar tidak menabrak teks judul. Rincian "5/5 Misi Algoritma" telah dihilangkan dan diganti dengan rincian waktu final, skor final, serta waktu kualifikasi.
  5. **Pengurutan Switcher Batch Sesuai Best Practice LTR:**
     - **Kiri:** Batch 1 / Edisi 2025 (27 November 2025 - Arsip/Tahun Lalu)
     - **Kanan:** Batch 2 / Edisi 2026 (26 November 2026 - Terbaru/Tahun Ini, aktif secara default)
  6. **Lokasi:** Terstandardisasi menjadi **"Kantor BBLSDM Komdigi Makassar"**.
  7. **Tabel Interaktif Berdasarkan Babak:** Tab filter beralih instan antara *Babak Kualifikasi (30 Peserta)* di kiri, *Babak Final (10 Finalis)* di tengah, dan *Podium Juara (1, 2, 3)* di kanan. Kolom status anti-terpotong (`whitespace-nowrap`).
  8. **Header Dinamis Adaptif (*Dark Cyber Header*):** Navbar khusus halaman Speed Coding kini otomatis beradaptasi dengan warna latar halaman (`bg-slate-950/95` dengan *border* `slate-800`), sementara pada halaman lainnya tetap mempertahankan warna biru resmi `bg-blue-600`. Seluruh fungsi, menu, dan responsivitas tetap 100% sama.
  9. **Optimalisasi Responsivitas View Laptop (Anti-Tabrakan & Anti-Truncate):**
     - **Navigasi Mengambang Kiri (*Quick Nav Dock*):** Breakpoint diubah menjadi `hidden 2xl:block` sehingga hanya tampil pada layar monitor lebar PC ($\ge 1536\text{px}$) yang memiliki sisa *gutter* aman. Pada layar laptop (1280px - 1440px), dock disembunyikan agar tidak menabrak dan menutupi kolom tabel peringkat.
     - **Kolom Maturitas Tier & Tabel Beranda:** Ditambahkan `whitespace-nowrap`, lebar minimum kolom (`min-w-[140px]`), dan `min-w-[860px]` pada tabel dengan `overflow-x-auto`. Tulisan badge seperti *"Unggul Digital"* tidak akan lagi patah 2 baris atau terpotong pada layar laptop.

### 5. Standardisasi Instansi BBLSDM & Indikator Loading Modern
* **Perubahan Nomenklatur:** Seluruh teks instansi distandardisasi menjadi **"BBLSDM Komdigi Makassar"** dan judul menjadi **"Kompetisi Speed Coding"**.
* **Indikator Loading Halus (No White Flash):** Efek layar memutih (*full-screen white fade*) telah dihapus sepenuhnya dan digantikan dengan **Top Glowing Progress Bar** (gaya YouTube / GitHub / NProgress) di bagian atas layar (`z-index: 99999`) dengan gradien amber-biru. Halaman tetap terlihat utuh dan transisi terasa instan.

### 6. Integrasi Email SMTP Gmail & Sistem Cadangan Tautan Unik (Anti-Spam)
* **Koneksi SMTP:** Terhubung ke `smtp.gmail.com:465` (SSL) menggunakan App Password 16-digit. Telah diuji langsung dan berhasil mengirimkan email notifikasi kredensial.
* **Mekanisme Cadangan Tautan Mandiri (Bypass Email):**
  1. Pada saat Admin menekan "Terbitkan Akun", sistem membuat token aman via `Password::broker()`.
  2. Muncul kartu kredensial instan yang menampilkan password sementara, tautan cadangan aktivasi mandiri (`/reset-password/{token}`), dan tombol langsung *"Kirim WhatsApp PIC"*.
  3. Jika email terindikasi spam / tidak sampai, admin dapat langsung menyalin tautan atau meneruskannya ke kontak WA operator sekolah.
  4. Tersedia tabel riwayat penerbitan akun dengan tombol *"Buat Tautan Reset Baru"* jika sekolah memerlukan reset di masa mendatang.

### 7. Perbaikan Kotak Hitam pada Poligon Peta Leaflet (Focus Outline Bounding Box)
* **Penyebab:** Pada browser modern berbasis Chromium (Google Chrome/Microsoft Edge), saat elemen SVG `<path>` wilayah pada Leaflet diklik, peramban secara otomatis menerapkan *default keyboard focus ring* (`:focus` / `:focus-visible`) mengelilingi batas terluar (*bounding box* / `getBBox()`) elemen tersebut.
* Karena wilayah seperti **Pangkajene dan Kepulauan (Pangkep)** memiliki pulau-pulau terluar hingga Laut Jawa dan Selat Makassar (Liukang Tupabbiring, Liukang Tangaya, Kalukalukuang), peramban menggambar kotak persegi panjang hitam raksasa yang membentang dari daratan utama hingga ke tengah laut.
* **Solusi yang Diterapkan:**
  1. Menambahkan aturan CSS global di `layouts/app.blade.php`:
     ```css
     .leaflet-container path:focus,
     .leaflet-container path:focus-visible,
     .leaflet-container svg:focus,
     path.leaflet-interactive:focus,
     .leaflet-interactive:focus {
         outline: none !important;
         box-shadow: none !important;
     }
     ```
  2. Menambahkan `e.originalEvent.target.blur()` pada event `click` GeoJSON layer di `landing.blade.php` dan `landing_v2.blade.php` agar elemen poligon tidak menahan status fokus peramban setelah diklik. Kotak hitam hilang sepenuhnya.

### 8. Panel Manajemen & Edit Speed Coding Scratch (Role Admin)
* **Tabel Database Baru:** Dibuat tabel `speed_coding_participants` dan model `SpeedCodingParticipant` yang menyimpan data peringkat, waktu tempuh, skor akurasi kualifikasi (30 peserta), skor final (10 finalis), jenjang (SD/SMP), status juara, dan batch/tahun edisi.
* **Fitur Halaman Admin (`/admin/speed-coding`):**
  1. **Menu Sidebar Admin:** Menu khusus **"Speed Coding"** dengan ikon petir di panel sidebar admin.
  2. **Pengalih Edisi / Batch:** Admin dapat memilih edisi tahun (2026 / 2025) atau menambahkan edisi baru secara dinamis.
  3. **Tambah & Edit Peserta Manual:** Tersedia modal interaktif untuk memasukkan/mengubah data peserta lengkap dengan validasi skor kualifikasi dan babak final.
  4. **Upload File Excel / CSV dengan Live Preview:**
     - Menggunakan pustaka *SheetJS* di sisi peramban. Saat admin memilih/menyeret file `.xlsx`, `.xls`, atau `.csv`, tabel pratinjau (*preview*) langsung ditampilkan secara instan sebelum disimpan.
     - Admin dapat memeriksa seluruh baris data hasil pembacaan berkas Excel.
     - Pilihan metode: *"Ganti semua data tahun ini (Replace All)"* atau *"Tambahkan ke data yang ada (Append)"*.
  5. **Unduh Template Excel Resmi Berstandar Web:**
     - Tombol download tersedia baik di toolbar atas maupun di dalam modal upload.
     - Menyediakan berkas `.xlsx` dengan 2 lembar (*sheet*): *Data Peserta* (lebar kolom rapi, 11 kolom resmi) dan *Petunjuk Pengisian* (aturan tipe data & format waktu `mm:ss`).
     - Tersedia rute server alternatif `/admin/speed-coding/template` untuk mengunduh versi `.csv` ber-BOM UTF-8.
  6. **Sinkronisasi Real-Time Frontend:** Halaman publik `/speed-coding` secara otomatis mengambil data terbaru dari database.

### 9. Sistem Dialog Modal Kustom HANDAL (Pengganti Popup Native Browser)
* **Latar Belakang:** Popup bawaan browser (`confirm()` dan `alert()` seperti `127.0.0.1:8000 says...`) tampak kaku, tidak profesional, dan mengganggu estetika antarmuka.
* **Solusi yang Diterapkan:**
  1. Dibuat komponen dialog kustom `handalModalDialog` pada `layouts/admin.blade.php` dengan desain modern:
     - Backdrop gelap dengan efek *blur* (`bg-slate-950/70 backdrop-blur-sm`).
     - Kartu modal melengkung halus (`rounded-3xl`) dengan ikon tematik dinamis (Perisai Biru untuk konfirmasi, Segitiga Kuning untuk peringatan, Tempat Sampah Merah untuk hapus data, Centang Hijau untuk sukses).
     - Tombol konfirmasi dengan pendaran halus (`Batal` vs `Lanjutkan`).
  2. Disediakan API global JavaScript:
     - `window.handalConfirm({ title, message, confirmText, cancelText, type })` berbasis Promise.
     - `window.handalAlert({ title, message, buttonText, type })`.
     - `window.handalConfirmForm(formElement, message, title, type)` untuk mengintersepsi submit form.
  3. Seluruh konfirmasi aksi penting (Penerbitan Akun Sekolah, Hapus Peserta Speed Coding, Hapus Kategori/Pertanyaan, dan Konfirmasi Upload Excel) kini menggunakan dialog modal kustom ini tanpa ada lagi popup browser `127.0.0.1 says`.




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
* `resources/views/pages/ranking.blade.php` *(Modifikasi)*: Pembersihan tombol dan indikasi halaman pemeringkatan ganda. Satu halaman peringkat tunggal.
* `resources/views/student_survey/show.blade.php`, `closed.blade.php`, `thank_you.blade.php` *(Baru)*: Antarmuka kuesioner siswa.
* `README.md` *(Modifikasi)*: Dokumentasi lengkap proyek dalam bahasa Indonesia.

---
*Catatan: Dokumen ini siap dihapus pada commit selanjutnya di perangkat lain.*
