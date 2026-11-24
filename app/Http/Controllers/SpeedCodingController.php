<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpeedCodingBatch;
use App\Models\SpeedCodingParticipant;
use Illuminate\Support\Facades\Auth;

class SpeedCodingController extends Controller
{
    /**
     * Tampilkan halaman khusus Kompetisi Speed Coding Scratch.
     * Batch 1: 27 November 2025 (Arsip / Tahun Lalu)
     * Batch 2: 26 November 2026 (Terbaru / Tahun Ini - Coming Soon)
     * Kategori: Terbuka untuk SD (Kelas 5-6) & SMP (Kelas 1-3) digabung dalam satu perlombaan.
     */
    public function index(Request $request)
    {
        // Pastikan batch standar 2025 dan 2026 selalu terdaftar
        $batches = SpeedCodingBatch::orderBy('year', 'asc')->get();
        if ($batches->isEmpty()) {
            SpeedCodingBatch::create([
                'year' => '2025',
                'batch_name' => 'Batch 1',
                'title' => 'HANDAL Speed Coding Scratch Competition 2025',
                'subtitle' => 'Edisi Perdana: Pemrograman Logika Scratch, Animasi Interaktif & Keamanan Informasi Siswa SD-SMP',
                'date' => '27 November 2025',
                'location' => 'BBLSDM Komdigi Makassar',
                'is_published' => true,
            ]);
            SpeedCodingBatch::create([
                'year' => '2026',
                'batch_name' => 'Batch 2',
                'title' => 'HANDAL Speed Coding Scratch Championship 2026',
                'subtitle' => 'Kompetisi Pemrograman Cepat dengan Aplikasi Scratch untuk Pelajar SD (Kelas 5-6) & SMP (Kelas 1-3)',
                'date' => '26 November 2026',
                'location' => 'BBLSDM Komdigi Makassar',
                'is_published' => false,
            ]);
            $batches = SpeedCodingBatch::orderBy('year', 'asc')->get();
        }

        $isAdmin = Auth::check() && Auth::user()->role === 'admin';
        $allowPreview = $isAdmin || $request->has('preview');

        $competitions = [];

        $allParticipants = SpeedCodingParticipant::all()->groupBy('year');

        foreach ($batches as $batch) {
            $year = $batch->year;
            $dbParticipants = $allParticipants->get($year, collect());

            $participants = $dbParticipants->map(function ($p) {
                return [
                    'id'                => $p->id,
                    'kualifikasi_rank'  => $p->kualifikasi_rank,
                    'final_rank'        => $p->final_rank,
                    'name'              => $p->name,
                    'school'            => $p->school,
                    'jenjang'           => $p->jenjang,
                    'kelas'             => $p->kelas ?? $p->jenjang,
                    'language'          => $p->language ?? 'Scratch 3.0',
                    'kualifikasi_time'  => $p->kualifikasi_time,
                    'kualifikasi_score' => (float)$p->kualifikasi_score,
                    'final_time'        => $p->final_time,
                    'final_score'       => $p->final_score !== null ? (float)$p->final_score : null,
                    'is_finalist'       => (bool)$p->is_finalist,
                    'status'            => $p->status,
                    'avatar'            => $p->avatar ?? SpeedCodingParticipant::generateAvatar($p->name),
                ];
            })->toArray();

            // Urutkan peserta: Finalis urut berdasarkan final_rank, sisanya urut kualifikasi_rank
            usort($participants, function ($a, $b) {
                if (!empty($a['final_rank']) && !empty($b['final_rank'])) {
                    return $a['final_rank'] <=> $b['final_rank'];
                }
                if (!empty($a['final_rank'])) return -1;
                if (!empty($b['final_rank'])) return 1;
                return ($a['kualifikasi_rank'] ?? 999) <=> ($b['kualifikasi_rank'] ?? 999);
            });

            // Top 3 Podium (Juara 1, 2, 3)
            $winners = array_filter($participants, fn($p) => !empty($p['final_rank']) && $p['final_rank'] <= 3);
            usort($winners, fn($a, $b) => $a['final_rank'] <=> $b['final_rank']);
            $winners = array_values($winners);

            $podium = [
                'first' => isset($winners[0]) ? [
                    'rank'             => 1,
                    'name'             => $winners[0]['name'],
                    'school'           => $winners[0]['school'],
                    'kelas'            => $winners[0]['kelas'],
                    'jenjang'          => $winners[0]['jenjang'],
                    'language'         => $winners[0]['language'],
                    'time'             => $winners[0]['final_time'],
                    'score'            => $winners[0]['final_score'],
                    'kualifikasi_time' => $winners[0]['kualifikasi_time'],
                    'badge'            => 'Gold Medalist 🥇',
                    'avatar'           => $winners[0]['avatar']
                ] : null,
                'second' => isset($winners[1]) ? [
                    'rank'             => 2,
                    'name'             => $winners[1]['name'],
                    'school'           => $winners[1]['school'],
                    'kelas'            => $winners[1]['kelas'],
                    'jenjang'          => $winners[1]['jenjang'],
                    'language'         => $winners[1]['language'],
                    'time'             => $winners[1]['final_time'],
                    'score'            => $winners[1]['final_score'],
                    'kualifikasi_time' => $winners[1]['kualifikasi_time'],
                    'badge'            => 'Silver Medalist 🥈',
                    'avatar'           => $winners[1]['avatar']
                ] : null,
                'third' => isset($winners[2]) ? [
                    'rank'             => 3,
                    'name'             => $winners[2]['name'],
                    'school'           => $winners[2]['school'],
                    'kelas'            => $winners[2]['kelas'],
                    'jenjang'          => $winners[2]['jenjang'],
                    'language'         => $winners[2]['language'],
                    'time'             => $winners[2]['final_time'],
                    'score'            => $winners[2]['final_score'],
                    'kualifikasi_time' => $winners[2]['kualifikasi_time'],
                    'badge'            => 'Bronze Medalist 🥉',
                    'avatar'           => $winners[2]['avatar']
                ] : null,
            ];

            // Juara Harapan 1, 2, 3 (Khusus untuk edisi 2025 sesuai data asli)
            $harapan = [];
            if ($year === '2025') {
                $harapanRaw = array_filter($participants, fn($p) => !empty($p['final_rank']) && in_array($p['final_rank'], [4, 5, 6]));
                usort($harapanRaw, fn($a, $b) => $a['final_rank'] <=> $b['final_rank']);
                $harapanRaw = array_values($harapanRaw);

                foreach ($harapanRaw as $h) {
                    $harapanRank = $h['final_rank'] - 3; // 4->1, 5->2, 6->3
                    $harapan[] = [
                        'harapan_rank'     => $harapanRank,
                        'final_rank'       => $h['final_rank'],
                        'title'            => "Juara Harapan {$harapanRank}",
                        'name'             => $h['name'],
                        'school'           => $h['school'],
                        'kelas'            => $h['kelas'],
                        'jenjang'          => $h['jenjang'],
                        'score'            => $h['final_score'],
                        'time'             => $h['final_time'],
                        'kualifikasi_time' => $h['kualifikasi_time'],
                        'badge'            => "Harapan {$harapanRank} 🎖️",
                        'avatar'           => $h['avatar']
                    ];
                }
            }

            $competitions[$year] = [
                'year'               => (string)$year,
                'batch_name'         => $batch->batch_name ?? "Batch {$year}",
                'title'              => $batch->title ?? "HANDAL Speed Coding Scratch Championship {$year}",
                'subtitle'           => $batch->subtitle ?? 'Kompetisi Pemrograman Cepat dengan Aplikasi Scratch untuk Pelajar SD (Kelas 5-6) & SMP (Kelas 1-3)',
                'date'               => $batch->date ?? "Tahun {$year}",
                'location'           => 'BBLSDM Komdigi Makassar',
                'is_published'       => (bool)$batch->is_published,
                'total_participants' => count($participants),
                'finalists_count'    => count(array_filter($participants, fn($p) => !empty($p['is_finalist']))),
                'podium'             => $podium,
                'harapan'            => $harapan,
                'has_harapan'        => count($harapan) > 0,
                'participants'       => $participants
            ];
        }

        return view('pages.speed_coding', compact('competitions', 'isAdmin', 'allowPreview'));
    }
}
