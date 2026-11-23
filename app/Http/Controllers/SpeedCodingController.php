<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeedCodingController extends Controller
{
    /**
     * Tampilkan halaman khusus Kompetisi Speed Coding Scratch.
     * Batch 1: 27 November 2025 (Arsip / Tahun Lalu)
     * Batch 2: 26 November 2026 (Terbaru / Tahun Ini)
     * Kategori: Terbuka untuk SD (Kelas 5-6) & SMP (Kelas 1-3) digabung dalam satu perlombaan.
     */
    public function index()
    {
        // 1. Cek apakah ada data di tabel database speed_coding_participants
        if (\App\Models\SpeedCodingParticipant::count() > 0) {
            $allYears = \App\Models\SpeedCodingParticipant::distinct()->pluck('year')->sortDesc()->values();
            $competitions = [];

            foreach ($allYears as $year) {
                $dbParticipants = \App\Models\SpeedCodingParticipant::where('year', $year)->get();
                $participants = $dbParticipants->map(function ($p) {
                    return [
                        'kualifikasi_rank' => $p->kualifikasi_rank,
                        'final_rank' => $p->final_rank,
                        'name' => $p->name,
                        'school' => $p->school,
                        'jenjang' => $p->jenjang,
                        'kelas' => $p->kelas,
                        'language' => $p->language ?? 'Scratch 3.0',
                        'kualifikasi_time' => $p->kualifikasi_time,
                        'kualifikasi_score' => (float)$p->kualifikasi_score,
                        'final_time' => $p->final_time,
                        'final_score' => $p->final_score !== null ? (float)$p->final_score : null,
                        'is_finalist' => (bool)$p->is_finalist,
                        'status' => $p->status,
                        'avatar' => $p->avatar ?? \App\Models\SpeedCodingParticipant::generateAvatar($p->name),
                    ];
                })->toArray();

                // Sort: Finalis urut berdasarkan final_rank, non-finalis urut berdasarkan kualifikasi_rank
                usort($participants, function ($a, $b) {
                    if (!empty($a['final_rank']) && !empty($b['final_rank'])) {
                        return $a['final_rank'] <=> $b['final_rank'];
                    }
                    if (!empty($a['final_rank'])) return -1;
                    if (!empty($b['final_rank'])) return 1;
                    return ($a['kualifikasi_rank'] ?? 999) <=> ($b['kualifikasi_rank'] ?? 999);
                });

                // Top 3 Podium
                $winners = array_filter($participants, fn($p) => !empty($p['final_rank']) && $p['final_rank'] <= 3);
                usort($winners, fn($a, $b) => $a['final_rank'] <=> $b['final_rank']);
                $winners = array_values($winners);

                $podium = [
                    'first' => isset($winners[0]) ? [
                        'rank' => 1,
                        'name' => $winners[0]['name'],
                        'school' => $winners[0]['school'],
                        'kelas' => $winners[0]['kelas'],
                        'jenjang' => $winners[0]['jenjang'],
                        'language' => $winners[0]['language'],
                        'time' => $winners[0]['final_time'],
                        'score' => $winners[0]['final_score'],
                        'kualifikasi_time' => $winners[0]['kualifikasi_time'],
                        'badge' => 'Gold Medalist 🥇',
                        'avatar' => $winners[0]['avatar']
                    ] : null,
                    'second' => isset($winners[1]) ? [
                        'rank' => 2,
                        'name' => $winners[1]['name'],
                        'school' => $winners[1]['school'],
                        'kelas' => $winners[1]['kelas'],
                        'jenjang' => $winners[1]['jenjang'],
                        'language' => $winners[1]['language'],
                        'time' => $winners[1]['final_time'],
                        'score' => $winners[1]['final_score'],
                        'kualifikasi_time' => $winners[1]['kualifikasi_time'],
                        'badge' => 'Silver Medalist 🥈',
                        'avatar' => $winners[1]['avatar']
                    ] : null,
                    'third' => isset($winners[2]) ? [
                        'rank' => 3,
                        'name' => $winners[2]['name'],
                        'school' => $winners[2]['school'],
                        'kelas' => $winners[2]['kelas'],
                        'jenjang' => $winners[2]['jenjang'],
                        'language' => $winners[2]['language'],
                        'time' => $winners[2]['final_time'],
                        'score' => $winners[2]['final_score'],
                        'kualifikasi_time' => $winners[2]['kualifikasi_time'],
                        'badge' => 'Bronze Medalist 🥉',
                        'avatar' => $winners[2]['avatar']
                    ] : null,
                ];

                $competitions[$year] = [
                    'year' => (string)$year,
                    'batch_name' => $year == '2026' ? 'Batch 2' : ($year == '2025' ? 'Batch 1' : 'Batch ' . $year),
                    'title' => "HANDAL Speed Coding Scratch Championship {$year}",
                    'subtitle' => 'Kompetisi Pemrograman Cepat dengan Aplikasi Scratch untuk Pelajar SD (Kelas 5-6) & SMP (Kelas 1-3)',
                    'date' => $year == '2026' ? '26 November 2026' : ($year == '2025' ? '27 November 2025' : "Tahun {$year}"),
                    'location' => 'Kantor BBLSDM Komdigi Makassar',
                    'total_participants' => count($participants),
                    'finalists_count' => count(array_filter($participants, fn($p) => !empty($p['is_finalist']))),
                    'podium' => $podium,
                    'participants' => $participants
                ];
            }

            return view('pages.speed_coding', compact('competitions'));
        }

        // 2. Fallback jika database belum memiliki data
        $competitions = [
            '2026' => [
                'year'                => '2026',
                'batch_name'          => 'Batch 2',
                'title'               => 'HANDAL Speed Coding Scratch Championship 2026 (Batch 2)',
                'subtitle'            => 'Kompetisi Pemrograman Cepat dengan Aplikasi Scratch untuk Pelajar SD (Kelas 5-6) & SMP (Kelas 1-3)',
                'date'                => '26 November 2026',
                'location'            => 'Kantor BBLSDM Komdigi Makassar',
                'total_participants'  => 30,
                'finalists_count'     => 10,
                'podium' => [
                    'first' => [
                        'rank'             => 1,
                        'name'             => 'Ahmad Zaki Fauzan',
                        'school'           => 'SMP Islam Athirah Makassar',
                        'kelas'            => 'Kelas 9 SMP',
                        'jenjang'          => 'SMP',
                        'language'         => 'Scratch 3.0',
                        'time'             => '03:14',
                        'score'            => 100.0,
                        'kualifikasi_time' => '03:45',
                        'badge'            => 'Gold Medalist 🥇',
                        'avatar'           => 'AZ'
                    ],
                    'second' => [
                        'rank'             => 2,
                        'name'             => 'Zahra Amalia Khairunnisa',
                        'school'           => 'SD Unggulan BTN Pemda',
                        'kelas'            => 'Kelas 6 SD',
                        'jenjang'          => 'SD',
                        'language'         => 'Scratch 3.0',
                        'time'             => '03:42',
                        'score'            => 98.0,
                        'kualifikasi_time' => '03:38',
                        'badge'            => 'Silver Medalist 🥈',
                        'avatar'           => 'ZA'
                    ],
                    'third' => [
                        'rank'             => 3,
                        'name'             => 'Siti Nurhaliza',
                        'school'           => 'SMPN 6 Makassar',
                        'kelas'            => 'Kelas 8 SMP',
                        'jenjang'          => 'SMP',
                        'language'         => 'Scratch 3.0',
                        'time'             => '04:05',
                        'score'            => 95.5,
                        'kualifikasi_time' => '04:12',
                        'badge'            => 'Bronze Medalist 🥉',
                        'avatar'           => 'SN'
                    ]
                ],
                'participants' => $this->get2026Participants()
            ],
            '2025' => [
                'year'                => '2025',
                'batch_name'          => 'Batch 1',
                'title'               => 'HANDAL Speed Coding Scratch Competition 2025 (Batch 1)',
                'subtitle'            => 'Edisi Perdana: Pemrograman Logika Scratch, Animasi Interaktif & Keamanan Informasi Siswa SD-SMP',
                'date'                => '27 November 2025',
                'location'            => 'Kantor BBLSDM Komdigi Makassar',
                'total_participants'  => 30,
                'finalists_count'     => 10,
                'podium' => [
                    'first' => [
                        'rank'             => 1,
                        'name'             => 'Kinan Aisyah Wardoyo',
                        'school'           => 'SMPN 6 Makassar',
                        'kelas'            => 'Kelas 8 SMP',
                        'jenjang'          => 'SMP',
                        'language'         => 'Scratch 3.0',
                        'time'             => '03:35',
                        'score'            => 100.0,
                        'kualifikasi_time' => '03:50',
                        'badge'            => 'Gold Medalist 🥇',
                        'avatar'           => 'KA'
                    ],
                    'second' => [
                        'rank'             => 2,
                        'name'             => 'Lionel Alexander',
                        'school'           => 'SD Katolik Santo Yakobus',
                        'kelas'            => 'Kelas 5 SD',
                        'jenjang'          => 'SD',
                        'language'         => 'Scratch 3.0',
                        'time'             => '03:58',
                        'score'            => 97.0,
                        'kualifikasi_time' => '03:42',
                        'badge'            => 'Silver Medalist 🥈',
                        'avatar'           => 'LA'
                    ],
                    'third' => [
                        'rank'             => 3,
                        'name'             => 'Rahmat Hidayatullah',
                        'school'           => 'SMP Islam Athirah 1',
                        'kelas'            => 'Kelas 7 SMP',
                        'jenjang'          => 'SMP',
                        'language'         => 'Scratch 3.0',
                        'time'             => '04:19',
                        'score'            => 94.0,
                        'kualifikasi_time' => '04:30',
                        'badge'            => 'Bronze Medalist 🥉',
                        'avatar'           => 'RH'
                    ]
                ],
                'participants' => $this->get2025Participants()
            ]
        ];

        return view('pages.speed_coding', compact('competitions'));
    }

    /**
     * Data 30 Peserta Batch 2 (26 November 2026).
     * SD & SMP digabung. Terdapat 2 tahap: Babak Kualifikasi (30 peserta) & Babak Final (10 peserta lolos).
     */
    private function get2026Participants()
    {
        return [
            [
                'final_rank' => 1, 'kualifikasi_rank' => 2,
                'name' => 'Ahmad Zaki Fauzan', 'school' => 'SMP Islam Athirah Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '03:45', 'kualifikasi_score' => 98.0,
                'final_time' => '03:14', 'final_score' => 100.0,
                'is_finalist' => true, 'status' => 'Juara 1 🥇'
            ],
            [
                'final_rank' => 2, 'kualifikasi_rank' => 1,
                'name' => 'Zahra Amalia Khairunnisa', 'school' => 'SD Unggulan BTN Pemda', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '03:38', 'kualifikasi_score' => 99.0,
                'final_time' => '03:42', 'final_score' => 98.0,
                'is_finalist' => true, 'status' => 'Juara 2 🥈'
            ],
            [
                'final_rank' => 3, 'kualifikasi_rank' => 3,
                'name' => 'Siti Nurhaliza', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:12', 'kualifikasi_score' => 94.0,
                'final_time' => '04:05', 'final_score' => 95.5,
                'is_finalist' => true, 'status' => 'Juara 3 🥉'
            ],
            [
                'final_rank' => 4, 'kualifikasi_rank' => 5,
                'name' => 'Muhammad Kenzo Alvaro', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:35', 'kualifikasi_score' => 92.0,
                'final_time' => '04:22', 'final_score' => 93.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 5, 'kualifikasi_rank' => 4,
                'name' => 'Nayla Putri Wardani', 'school' => 'SD Islam Athirah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:20', 'kualifikasi_score' => 93.5,
                'final_time' => '04:38', 'final_score' => 91.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 6, 'kualifikasi_rank' => 7,
                'name' => 'Khalid Basalamah R.', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:02', 'kualifikasi_score' => 89.0,
                'final_time' => '04:51', 'final_score' => 90.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 7, 'kualifikasi_rank' => 6,
                'name' => 'Rayhan Gunawan', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:48', 'kualifikasi_score' => 90.5,
                'final_time' => '05:08', 'final_score' => 88.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 8, 'kualifikasi_rank' => 9,
                'name' => 'Annisa Salsabila', 'school' => 'SD Kompleks Sambung Jawa', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:25', 'kualifikasi_score' => 86.0,
                'final_time' => '05:19', 'final_score' => 87.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 9, 'kualifikasi_rank' => 8,
                'name' => 'Gisella Anastasya L.', 'school' => 'SMP Dian Harapan Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:15', 'kualifikasi_score' => 87.5,
                'final_time' => '05:32', 'final_score' => 85.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 10, 'kualifikasi_rank' => 10,
                'name' => 'Muhammad Arka Fabian', 'school' => 'SD Negeri Mangkura 1', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:38', 'kualifikasi_score' => 85.0,
                'final_time' => '05:45', 'final_score' => 84.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 11,
                'name' => 'Clara Aurelia Wijaya', 'school' => 'SMP Kristen IPEKA Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:01', 'kualifikasi_score' => 82.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 12,
                'name' => 'Fadhil Anshori', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:15', 'kualifikasi_score' => 81.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 13,
                'name' => 'Kayla Natasha', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:28', 'kualifikasi_score' => 80.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 14,
                'name' => 'Dwi Santoso', 'school' => 'SD Inpres Mallengkeri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:40', 'kualifikasi_score' => 78.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 15,
                'name' => 'Safira Azzahra', 'school' => 'SMP Al-Azhar 24 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:52', 'kualifikasi_score' => 77.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 16,
                'name' => 'Brian Immanuel Tan', 'school' => 'SMP Zion Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:05', 'kualifikasi_score' => 76.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 17,
                'name' => 'Tiara Andini M.', 'school' => 'SD Unggulan Toddopuli', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:18', 'kualifikasi_score' => 74.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 18,
                'name' => 'Irfan Hakim Syaputra', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:30', 'kualifikasi_score' => 73.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 19,
                'name' => 'Alif Ramadhan', 'school' => 'SMP Wahdah Islamiyah', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:44', 'kualifikasi_score' => 71.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 20,
                'name' => 'Zidan Syauqi', 'school' => 'SD Pertiwi Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:56', 'kualifikasi_score' => 70.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 21,
                'name' => 'Fany Rahmania', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:10', 'kualifikasi_score' => 69.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 22,
                'name' => 'Dimas Prasetyo', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:22', 'kualifikasi_score' => 67.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 23,
                'name' => 'Aditya Nugraha', 'school' => 'SD Islam Terpadu Nurul Fikri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:35', 'kualifikasi_score' => 66.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 24,
                'name' => 'Rafi Ahmad Fauzi', 'school' => 'SD Inpres Perumnas Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:48', 'kualifikasi_score' => 65.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 25,
                'name' => 'Cindy Claudia', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:02', 'kualifikasi_score' => 63.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 26,
                'name' => 'Bintang Pratama', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:16', 'kualifikasi_score' => 62.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 27,
                'name' => 'Putri Ayu Wandira', 'school' => 'SD Sudirman Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:30', 'kualifikasi_score' => 60.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 28,
                'name' => 'Haikal Fikri', 'school' => 'SMP Dian Harapan Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:45', 'kualifikasi_score' => 59.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 29,
                'name' => 'Lukman Hakim', 'school' => 'SD Hang Tuah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '10:02', 'kualifikasi_score' => 57.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 30,
                'name' => 'Taufik Hidayatullah', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '10:20', 'kualifikasi_score' => 55.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
        ];
    }

    /**
     * Data 30 Peserta Batch 1 (27 November 2025).
     * SD & SMP digabung. Terdapat 2 tahap: Babak Kualifikasi (30 peserta) & Babak Final (10 peserta lolos).
     */
    private function get2025Participants()
    {
        return [
            [
                'final_rank' => 1, 'kualifikasi_rank' => 2,
                'name' => 'Kinan Aisyah Wardoyo', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '03:50', 'kualifikasi_score' => 97.5,
                'final_time' => '03:35', 'final_score' => 100.0,
                'is_finalist' => true, 'status' => 'Juara 1 🥇'
            ],
            [
                'final_rank' => 2, 'kualifikasi_rank' => 1,
                'name' => 'Lionel Alexander', 'school' => 'SD Katolik Santo Yakobus', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '03:42', 'kualifikasi_score' => 98.5,
                'final_time' => '03:58', 'final_score' => 97.0,
                'is_finalist' => true, 'status' => 'Juara 2 🥈'
            ],
            [
                'final_rank' => 3, 'kualifikasi_rank' => 3,
                'name' => 'Rahmat Hidayatullah', 'school' => 'SMP Islam Athirah 1', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:30', 'kualifikasi_score' => 92.0,
                'final_time' => '04:19', 'final_score' => 94.0,
                'is_finalist' => true, 'status' => 'Juara 3 🥉'
            ],
            [
                'final_rank' => 4, 'kualifikasi_rank' => 4,
                'name' => 'Fathia Rahmawati', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:45', 'kualifikasi_score' => 90.0,
                'final_time' => '04:36', 'final_score' => 91.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 5, 'kualifikasi_rank' => 6,
                'name' => 'Rania Salsabila', 'school' => 'SD Unggulan Toddopuli', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:08', 'kualifikasi_score' => 88.0,
                'final_time' => '04:52', 'final_score' => 89.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 6, 'kualifikasi_rank' => 5,
                'name' => 'Tania Amanda', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '04:55', 'kualifikasi_score' => 89.0,
                'final_time' => '05:07', 'final_score' => 87.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 7, 'kualifikasi_rank' => 8,
                'name' => 'Natasya Aurelia', 'school' => 'SMP Dian Harapan', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:35', 'kualifikasi_score' => 85.0,
                'final_time' => '05:22', 'final_score' => 86.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 8, 'kualifikasi_rank' => 7,
                'name' => 'Daffa Maulana', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:20', 'kualifikasi_score' => 86.5,
                'final_time' => '05:38', 'final_score' => 84.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 9, 'kualifikasi_rank' => 10,
                'name' => 'Khadijah Al-Kubro', 'school' => 'SMP Islam Athirah 1', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:00', 'kualifikasi_score' => 82.0,
                'final_time' => '05:51', 'final_score' => 83.0,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => 10, 'kualifikasi_rank' => 9,
                'name' => 'Nadira Syafira', 'school' => 'SD Negeri Mangkura 1', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '05:48', 'kualifikasi_score' => 83.5,
                'final_time' => '06:05', 'final_score' => 81.5,
                'is_finalist' => true, 'status' => 'Finalis Top 10 🎖️'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 11,
                'name' => 'Bima Sakti Yudha', 'school' => 'SMPN 6 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:20', 'kualifikasi_score' => 79.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 12,
                'name' => 'Fany Rahmania', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:33', 'kualifikasi_score' => 78.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 13,
                'name' => 'Erik Setiawan', 'school' => 'SMP Kristen IPEKA Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '06:47', 'kualifikasi_score' => 76.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 14,
                'name' => 'Bella Safira', 'school' => 'SD Kompleks Sambung Jawa', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:01', 'kualifikasi_score' => 75.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 15,
                'name' => 'Reza Pahlevi', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:15', 'kualifikasi_score' => 73.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 16,
                'name' => 'Hendra Wijaya', 'school' => 'SD Inpres Mallengkeri', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:28', 'kualifikasi_score' => 72.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 17,
                'name' => 'Cindy Claudia', 'school' => 'SMP Katolik Rajawali', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:42', 'kualifikasi_score' => 70.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 18,
                'name' => 'Rian Kurniawan', 'school' => 'SD Sudirman Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '07:55', 'kualifikasi_score' => 69.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 19,
                'name' => 'Gilang Ramadhan', 'school' => 'SMPN 12 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:08', 'kualifikasi_score' => 68.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 20,
                'name' => 'Rafi Ahmad Fauzi', 'school' => 'SD Inpres Perumnas', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:21', 'kualifikasi_score' => 66.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 21,
                'name' => 'Lukman Hakim', 'school' => 'SMPN 8 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:34', 'kualifikasi_score' => 65.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 22,
                'name' => 'Desi Ratnasari', 'school' => 'SD Pertiwi Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '08:48', 'kualifikasi_score' => 63.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 23,
                'name' => 'Pandu Wijaya', 'school' => 'SMP Zion Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:02', 'kualifikasi_score' => 62.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 24,
                'name' => 'Taufik Hidayatullah', 'school' => 'SD Hang Tuah Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:16', 'kualifikasi_score' => 60.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 25,
                'name' => 'Wahyu Hidayat', 'school' => 'SMP Islam Athirah Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:30', 'kualifikasi_score' => 59.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 26,
                'name' => 'Jonathan Hartanto', 'school' => 'SMP Dian Harapan', 'jenjang' => 'SMP', 'kelas' => 'Kelas 7 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:44', 'kualifikasi_score' => 57.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 27,
                'name' => 'Putri Ayu', 'school' => 'SD Unggulan BTN Pemda', 'jenjang' => 'SD', 'kelas' => 'Kelas 6 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '09:58', 'kualifikasi_score' => 56.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 28,
                'name' => 'Aditya Nugraha', 'school' => 'SMPN 1 Makassar', 'jenjang' => 'SMP', 'kelas' => 'Kelas 8 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '10:12', 'kualifikasi_score' => 54.5,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 29,
                'name' => 'Alif Ramadhan', 'school' => 'SD Telkom Makassar', 'jenjang' => 'SD', 'kelas' => 'Kelas 5 SD', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '10:28', 'kualifikasi_score' => 53.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
            [
                'final_rank' => null, 'kualifikasi_rank' => 30,
                'name' => 'Zidan Syauqi', 'school' => 'SMP Wahdah Islamiyah', 'jenjang' => 'SMP', 'kelas' => 'Kelas 9 SMP', 'language' => 'Scratch 3.0',
                'kualifikasi_time' => '10:45', 'kualifikasi_score' => 50.0,
                'final_time' => null, 'final_score' => null,
                'is_finalist' => false, 'status' => 'Peserta Kualifikasi'
            ],
        ];
    }
}
