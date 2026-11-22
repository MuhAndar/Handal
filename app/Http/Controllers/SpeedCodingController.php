<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeedCodingController extends Controller
{
    /**
     * Tampilkan halaman khusus Kompetisi Speed Coding Scratch (Tahun 2026 & 2025).
     * Jenjang Peserta: SD Kelas 5 s/d SMP Kelas 3.
     */
    public function index()
    {
        $competitions = [
            '2026' => [
                'year'        => '2026',
                'title'       => 'HANDAL National Speed Coding Scratch Championship 2026',
                'subtitle'    => 'Kompetisi Pemrograman Cepat dengan Aplikasi Scratch untuk Pelajar SD (Kelas 5-6) & SMP (Kelas 1-3 SMP)',
                'date'        => '18 November 2026',
                'location'    => 'Auditorium BBLSDM Komdigi Makassar & Daring (Hybrid)',
                'total_participants' => 30,
                'finalists_count'    => 10,
                'podium' => [
                    'first' => [
                        'rank'       => 1,
                        'name'       => 'Ahmad Zaki Fauzan',
                        'school'     => 'SMP Islam Athirah Makassar',
                        'kelas'      => 'Kelas 9 SMP (Kelas 3)',
                        'jenjang'    => 'SMP',
                        'language'   => 'Scratch 3.0',
                        'time'       => '03:14',
                        'score'      => 100.0,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Gold Medalist 🥇',
                        'avatar'     => 'AZ'
                    ],
                    'second' => [
                        'rank'       => 2,
                        'name'       => 'Zahra Amalia Khairunnisa',
                        'school'     => 'SD Unggulan BTN Pemda',
                        'kelas'      => 'Kelas 6 SD',
                        'jenjang'    => 'SD',
                        'language'   => 'Scratch 3.0',
                        'time'       => '03:42',
                        'score'      => 98.0,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Silver Medalist 🥈',
                        'avatar'     => 'ZA'
                    ],
                    'third' => [
                        'rank'       => 3,
                        'name'       => 'Siti Nurhaliza',
                        'school'     => 'SMPN 6 Makassar',
                        'kelas'      => 'Kelas 8 SMP (Kelas 2)',
                        'jenjang'    => 'SMP',
                        'language'   => 'Scratch 3.0',
                        'time'       => '04:05',
                        'score'      => 95.5,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Bronze Medalist 🥉',
                        'avatar'     => 'SN'
                    ]
                ],
                'participants' => $this->get2026Participants()
            ],
            '2025' => [
                'year'        => '2025',
                'title'       => 'HANDAL Speed Coding Scratch Competition 2025',
                'subtitle'    => 'Edisi Perdana: Pemrograman Logika Scratch, Animasi Interaktif & Keamanan Informasi Siswa SD-SMP',
                'date'        => '24 November 2025',
                'location'    => 'Lab Komputer BBLSDM Komdigi Makassar',
                'total_participants' => 30,
                'finalists_count'    => 10,
                'podium' => [
                    'first' => [
                        'rank'       => 1,
                        'name'       => 'Kinan Aisyah Wardoyo',
                        'school'     => 'SMPN 6 Makassar',
                        'kelas'      => 'Kelas 8 SMP (Kelas 2)',
                        'jenjang'    => 'SMP',
                        'language'   => 'Scratch 3.0',
                        'time'       => '03:35',
                        'score'      => 100.0,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Gold Medalist 🥇',
                        'avatar'     => 'KA'
                    ],
                    'second' => [
                        'rank'       => 2,
                        'name'       => 'Lionel Alexander',
                        'school'     => 'SD Katolik Santo Yakobus',
                        'kelas'      => 'Kelas 5 SD',
                        'jenjang'    => 'SD',
                        'language'   => 'Scratch 3.0',
                        'time'       => '03:58',
                        'score'      => 97.0,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Silver Medalist 🥈',
                        'avatar'     => 'LA'
                    ],
                    'third' => [
                        'rank'       => 3,
                        'name'       => 'Rahmat Hidayatullah',
                        'school'     => 'SMP Islam Athirah 1',
                        'kelas'      => 'Kelas 7 SMP (Kelas 1)',
                        'jenjang'    => 'SMP',
                        'language'   => 'Scratch 3.0',
                        'time'       => '04:19',
                        'score'      => 94.0,
                        'challenges' => '5/5 Misi Algoritma Selesai',
                        'badge'      => 'Bronze Medalist 🥉',
                        'avatar'     => 'RH'
                    ]
                ],
                'participants' => $this->get2025Participants()
            ]
        ];

        return view('pages.speed_coding', compact('competitions'));
    }

    /**
     * Data 30 Peserta Tahun 2026 (SD Kelas 5 s/d SMP Kelas 3 - Scratch)
     */
    private function get2026Participants()
    {
        return [
            ['rank' => 1,  'name' => 'Ahmad Zaki Fauzan', 'school' => 'SMP Islam Athirah Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '03:14', 'score' => 100.0, 'status' => 'Juara 1 🥇'],
            ['rank' => 2,  'name' => 'Zahra Amalia Khairunnisa', 'school' => 'SD Unggulan BTN Pemda', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '03:42', 'score' => 98.0, 'status' => 'Juara 2 🥈'],
            ['rank' => 3,  'name' => 'Siti Nurhaliza', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '04:05', 'score' => 95.5, 'status' => 'Juara 3 🥉'],
            ['rank' => 4,  'name' => 'Muhammad Kenzo Alvaro', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '04:22', 'score' => 93.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 5,  'name' => 'Nayla Putri Wardani', 'school' => 'SD Islam Athirah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '04:38', 'score' => 91.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 6,  'name' => 'Khalid Basalamah R.', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '04:51', 'score' => 90.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 7,  'name' => 'Rayhan Gunawan', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '05:08', 'score' => 88.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 8,  'name' => 'Annisa Salsabila', 'school' => 'SD Kompleks Sambung Jawa', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '05:19', 'score' => 87.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 9,  'name' => 'Gisella Anastasya L.', 'school' => 'SMP Dian Harapan Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '05:32', 'score' => 85.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 10, 'name' => 'Muhammad Arka Fabian', 'school' => 'SD Negeri Mangkura 1', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '05:45', 'score' => 84.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 11, 'name' => 'Clara Aurelia Wijaya', 'school' => 'SMP Kristen IPEKA Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '06:01', 'score' => 82.5, 'status' => 'Peserta'],
            ['rank' => 12, 'name' => 'Fadhil Anshori', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '06:15', 'score' => 81.0, 'status' => 'Peserta'],
            ['rank' => 13, 'name' => 'Kayla Natasha', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '06:28', 'score' => 80.0, 'status' => 'Peserta'],
            ['rank' => 14, 'name' => 'Dwi Santoso', 'school' => 'SD Inpres Mallengkeri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '06:40', 'score' => 78.5, 'status' => 'Peserta'],
            ['rank' => 15, 'name' => 'Safira Azzahra', 'school' => 'SMP Al-Azhar 24 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '06:52', 'score' => 77.0, 'status' => 'Peserta'],
            ['rank' => 16, 'name' => 'Brian Immanuel Tan', 'school' => 'SMP Zion Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '07:05', 'score' => 76.0, 'status' => 'Peserta'],
            ['rank' => 17, 'name' => 'Tiara Andini M.', 'school' => 'SD Unggulan Toddopuli', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '07:18', 'score' => 74.5, 'status' => 'Peserta'],
            ['rank' => 18, 'name' => 'Irfan Hakim Syaputra', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '07:30', 'score' => 73.0, 'status' => 'Peserta'],
            ['rank' => 19, 'name' => 'Alif Ramadhan', 'school' => 'SMP Wahdah Islamiyah', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '07:44', 'score' => 71.5, 'status' => 'Peserta'],
            ['rank' => 20, 'name' => 'Zidan Syauqi', 'school' => 'SD Pertiwi Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '07:56', 'score' => 70.0, 'status' => 'Peserta'],
            ['rank' => 21, 'name' => 'Fany Rahmania', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '08:10', 'score' => 69.0, 'status' => 'Peserta'],
            ['rank' => 22, 'name' => 'Dimas Prasetyo', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '08:22', 'score' => 67.5, 'status' => 'Peserta'],
            ['rank' => 23, 'name' => 'Aditya Nugraha', 'school' => 'SD Islam Terpadu Nurul Fikri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '08:35', 'score' => 66.0, 'status' => 'Peserta'],
            ['rank' => 24, 'name' => 'Rafi Ahmad Fauzi', 'school' => 'SD Inpres Perumnas Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '08:48', 'score' => 65.0, 'status' => 'Peserta'],
            ['rank' => 25, 'name' => 'Cindy Claudia', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '09:02', 'score' => 63.5, 'status' => 'Peserta'],
            ['rank' => 26, 'name' => 'Bintang Pratama', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '09:16', 'score' => 62.0, 'status' => 'Peserta'],
            ['rank' => 27, 'name' => 'Putri Ayu Wandira', 'school' => 'SD Sudirman Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '09:30', 'score' => 60.5, 'status' => 'Peserta'],
            ['rank' => 28, 'name' => 'Haikal Fikri', 'school' => 'SMP Dian Harapan Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '09:45', 'score' => 59.0, 'status' => 'Peserta'],
            ['rank' => 29, 'name' => 'Lukman Hakim', 'school' => 'SD Hang Tuah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '10:02', 'score' => 57.5, 'status' => 'Peserta'],
            ['rank' => 30, 'name' => 'Taufik Hidayatullah', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '10:20', 'score' => 55.0, 'status' => 'Peserta'],
        ];
    }

    /**
     * Data 30 Peserta Tahun 2025 (SD Kelas 5 s/d SMP Kelas 3 - Scratch)
     */
    private function get2025Participants()
    {
        return [
            ['rank' => 1,  'name' => 'Kinan Aisyah Wardoyo', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '03:35', 'score' => 100.0, 'status' => 'Juara 1 🥇'],
            ['rank' => 2,  'name' => 'Lionel Alexander', 'school' => 'SD Katolik Santo Yakobus', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '03:58', 'score' => 97.0, 'status' => 'Juara 2 🥈'],
            ['rank' => 3,  'name' => 'Rahmat Hidayatullah', 'school' => 'SMP Islam Athirah 1', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '04:19', 'score' => 94.0, 'status' => 'Juara 3 🥉'],
            ['rank' => 4,  'name' => 'Fathia Rahmawati', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '04:36', 'score' => 91.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 5,  'name' => 'Rania Salsabila', 'school' => 'SD Unggulan Toddopuli', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '04:52', 'score' => 89.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 6,  'name' => 'Tania Amanda', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '05:07', 'score' => 87.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 7,  'name' => 'Natasya Aurelia', 'school' => 'SMP Dian Harapan', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '05:22', 'score' => 86.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 8,  'name' => 'Daffa Maulana', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '05:38', 'score' => 84.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 9,  'name' => 'Khadijah Al-Kubro', 'school' => 'SMP Islam Athirah 1', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '05:51', 'score' => 83.0, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 10, 'name' => 'Nadira Syafira', 'school' => 'SD Negeri Mangkura 1', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '06:05', 'score' => 81.5, 'status' => 'Finalis Top 10 🎖️'],
            ['rank' => 11, 'name' => 'Bima Sakti Yudha', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '06:20', 'score' => 79.5, 'status' => 'Peserta'],
            ['rank' => 12, 'name' => 'Fany Rahmania', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '06:33', 'score' => 78.0, 'status' => 'Peserta'],
            ['rank' => 13, 'name' => 'Erik Setiawan', 'school' => 'SMP Kristen IPEKA Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '06:47', 'score' => 76.5, 'status' => 'Peserta'],
            ['rank' => 14, 'name' => 'Bella Safira', 'school' => 'SD Kompleks Sambung Jawa', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '07:01', 'score' => 75.0, 'status' => 'Peserta'],
            ['rank' => 15, 'name' => 'Reza Pahlevi', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '07:15', 'score' => 73.5, 'status' => 'Peserta'],
            ['rank' => 16, 'name' => 'Hendra Wijaya', 'school' => 'SD Inpres Mallengkeri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '07:28', 'score' => 72.0, 'status' => 'Peserta'],
            ['rank' => 17, 'name' => 'Cindy Claudia', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '07:42', 'score' => 70.5, 'status' => 'Peserta'],
            ['rank' => 18, 'name' => 'Rian Kurniawan', 'school' => 'SD Sudirman Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '07:55', 'score' => 69.0, 'status' => 'Peserta'],
            ['rank' => 19, 'name' => 'Gilang Ramadhan', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '08:08', 'score' => 68.0, 'status' => 'Peserta'],
            ['rank' => 20, 'name' => 'Rafi Ahmad Fauzi', 'school' => 'SD Inpres Perumnas', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '08:21', 'score' => 66.5, 'status' => 'Peserta'],
            ['rank' => 21, 'name' => 'Lukman Hakim', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '08:34', 'score' => 65.0, 'status' => 'Peserta'],
            ['rank' => 22, 'name' => 'Desi Ratnasari', 'school' => 'SD Pertiwi Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '08:48', 'score' => 63.5, 'status' => 'Peserta'],
            ['rank' => 23, 'name' => 'Pandu Wijaya', 'school' => 'SMP Zion Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '09:02', 'score' => 62.0, 'status' => 'Peserta'],
            ['rank' => 24, 'name' => 'Taufik Hidayatullah', 'school' => 'SD Hang Tuah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '09:16', 'score' => 60.5, 'status' => 'Peserta'],
            ['rank' => 25, 'name' => 'Wahyu Hidayat', 'school' => 'SMP Islam Athirah Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '09:30', 'score' => 59.0, 'status' => 'Peserta'],
            ['rank' => 26, 'name' => 'Jonathan Hartanto', 'school' => 'SMP Dian Harapan', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 (SMP 1)', 'language' => 'Scratch 3.0', 'time' => '09:44', 'score' => 57.5, 'status' => 'Peserta'],
            ['rank' => 27, 'name' => 'Putri Ayu', 'school' => 'SD Unggulan BTN Pemda', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0', 'time' => '09:58', 'score' => 56.0, 'status' => 'Peserta'],
            ['rank' => 28, 'name' => 'Aditya Nugraha', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 (SMP 2)', 'language' => 'Scratch 3.0', 'time' => '10:12', 'score' => 54.5, 'status' => 'Peserta'],
            ['rank' => 29, 'name' => 'Alif Ramadhan', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0', 'time' => '10:28', 'score' => 53.0, 'status' => 'Peserta'],
            ['rank' => 30, 'name' => 'Zidan Syauqi', 'school' => 'SMP Wahdah Islamiyah', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 (SMP 3)', 'language' => 'Scratch 3.0', 'time' => '10:45', 'score' => 50.0, 'status' => 'Peserta'],
        ];
    }
}
