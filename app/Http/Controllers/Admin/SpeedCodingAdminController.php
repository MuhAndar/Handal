<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpeedCodingParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpeedCodingAdminController extends Controller
{
    /**
     * Tampilkan halaman kelola peserta Speed Coding Scratch.
     */
    public function index(Request $request)
    {
        $availableYears = SpeedCodingParticipant::distinct()->pluck('year')->sortDesc()->values();
        if ($availableYears->isEmpty()) {
            $availableYears = collect(['2026', '2025']);
        }

        $selectedYear = $request->query('year', $availableYears->first() ?? '2026');

        $participants = SpeedCodingParticipant::where('year', $selectedYear)
            ->orderByRaw('CASE WHEN final_rank IS NOT NULL THEN final_rank ELSE 999 END ASC')
            ->orderByRaw('CASE WHEN kualifikasi_rank IS NOT NULL THEN kualifikasi_rank ELSE 999 END ASC')
            ->orderBy('id', 'asc')
            ->get();

        $stats = [
            'total' => $participants->count(),
            'finalists' => $participants->where('is_finalist', true)->count(),
            'winners' => $participants->where('is_winner', true)->count(),
            'sd' => $participants->where('jenjang', 'SD')->count(),
            'smp' => $participants->where('jenjang', 'SMP')->count(),
        ];

        $currentBatch = \App\Models\SpeedCodingBatch::firstOrCreate(
            ['year' => $selectedYear],
            [
                'batch_name' => $selectedYear == '2026' ? 'Batch 2' : ($selectedYear == '2025' ? 'Batch 1' : 'Batch ' . $selectedYear),
                'location' => 'BBLSDM Komdigi Makassar',
                'is_published' => ($selectedYear == '2025'),
            ]
        );

        return view('admin.speed_coding.index', compact(
            'participants',
            'availableYears',
            'selectedYear',
            'stats',
            'currentBatch'
        ));
    }

    /**
     * Toggle status publikasi edisi (Live vs Draf/Coming Soon).
     */
    public function togglePublish(Request $request, $year)
    {
        $batch = \App\Models\SpeedCodingBatch::firstOrCreate(
            ['year' => $year],
            [
                'batch_name' => $year == '2026' ? 'Batch 2' : 'Batch 1',
                'location' => 'BBLSDM Komdigi Makassar',
                'is_published' => false,
            ]
        );

        $batch->is_published = !$batch->is_published;
        $batch->save();

        $msg = $batch->is_published 
            ? "Edisi {$year} berhasil DIPUBLIKASIKAN ke publik!" 
            : "Edisi {$year} berhasil dialihkan ke mode DRAF (Coming Soon).";

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Simpan data peserta manual baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:10',
            'batch_name' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'jenjang' => 'required|in:SD,SMP',
            'kelas' => 'nullable|string|max:50',
            'kualifikasi_rank' => 'nullable|integer|min:1',
            'kualifikasi_time' => 'nullable|string|max:20',
            'kualifikasi_score' => 'nullable|numeric|min:0|max:100',
            'final_rank' => 'nullable|integer|min:1',
            'final_time' => 'nullable|string|max:20',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'is_finalist' => 'nullable|boolean',
            'status' => 'nullable|string|max:100',
        ]);

        $finalRank = !empty($validated['final_rank']) ? (int)$validated['final_rank'] : null;
        $isFinalist = !empty($validated['is_finalist']) || ($finalRank !== null && $finalRank <= 10);
        $isWinner = $finalRank !== null && $finalRank <= 3;

        // Auto badge / status jika kosong
        $status = $validated['status'] ?? null;
        if (empty($status)) {
            if ($finalRank === 1) $status = 'Juara 1 🥇';
            elseif ($finalRank === 2) $status = 'Juara 2 🥈';
            elseif ($finalRank === 3) $status = 'Juara 3 🥉';
            elseif ($isFinalist) $status = 'Finalis Top 10 🎖️';
            else $status = 'Peserta Kualifikasi';
        }

        SpeedCodingParticipant::create([
            'year' => $validated['year'],
            'batch_name' => $validated['batch_name'] ?? ($validated['year'] == '2026' ? 'Batch 2' : 'Batch 1'),
            'name' => $validated['name'],
            'school' => $validated['school'],
            'jenjang' => $validated['jenjang'],
            'kelas' => $validated['kelas'] ?? '',
            'kualifikasi_rank' => $validated['kualifikasi_rank'] ?? null,
            'kualifikasi_time' => $validated['kualifikasi_time'] ?? null,
            'kualifikasi_score' => $validated['kualifikasi_score'] ?? null,
            'final_rank' => $finalRank,
            'final_time' => $validated['final_time'] ?? null,
            'final_score' => $validated['final_score'] ?? null,
            'is_finalist' => $isFinalist,
            'is_winner' => $isWinner,
            'badge' => $status,
            'status' => $status,
            'language' => 'Scratch 3.0',
            'avatar' => SpeedCodingParticipant::generateAvatar($validated['name']),
        ]);

        return redirect()->route('admin.speed_coding.index', ['year' => $validated['year']])
            ->with('success', 'Data peserta ' . $validated['name'] . ' berhasil ditambahkan!');
    }

    /**
     * Update data peserta.
     */
    public function update(Request $request, $id)
    {
        $participant = SpeedCodingParticipant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'jenjang' => 'required|in:SD,SMP',
            'kelas' => 'nullable|string|max:50',
            'kualifikasi_rank' => 'nullable|integer|min:1',
            'kualifikasi_time' => 'nullable|string|max:20',
            'kualifikasi_score' => 'nullable|numeric|min:0|max:100',
            'final_rank' => 'nullable|integer|min:1',
            'final_time' => 'nullable|string|max:20',
            'final_score' => 'nullable|numeric|min:0|max:100',
            'is_finalist' => 'nullable|boolean',
            'status' => 'nullable|string|max:100',
        ]);

        $finalRank = !empty($validated['final_rank']) ? (int)$validated['final_rank'] : null;
        $isFinalist = !empty($validated['is_finalist']) || ($finalRank !== null && $finalRank <= 10);
        $isWinner = $finalRank !== null && $finalRank <= 3;

        $status = $validated['status'] ?? null;
        if (empty($status)) {
            if ($finalRank === 1) $status = 'Juara 1 🥇';
            elseif ($finalRank === 2) $status = 'Juara 2 🥈';
            elseif ($finalRank === 3) $status = 'Juara 3 🥉';
            elseif ($isFinalist) $status = 'Finalis Top 10 🎖️';
            else $status = 'Peserta Kualifikasi';
        }

        $participant->update([
            'name' => $validated['name'],
            'school' => $validated['school'],
            'jenjang' => $validated['jenjang'],
            'kelas' => $validated['kelas'] ?? '',
            'kualifikasi_rank' => $validated['kualifikasi_rank'] ?? null,
            'kualifikasi_time' => $validated['kualifikasi_time'] ?? null,
            'kualifikasi_score' => $validated['kualifikasi_score'] ?? null,
            'final_rank' => $finalRank,
            'final_time' => $validated['final_time'] ?? null,
            'final_score' => $validated['final_score'] ?? null,
            'is_finalist' => $isFinalist,
            'is_winner' => $isWinner,
            'badge' => $status,
            'status' => $status,
            'avatar' => SpeedCodingParticipant::generateAvatar($validated['name']),
        ]);

        return redirect()->route('admin.speed_coding.index', ['year' => $participant->year])
            ->with('success', 'Data peserta ' . $participant->name . ' berhasil diperbarui!');
    }

    /**
     * Hapus peserta.
     */
    public function destroy($id)
    {
        $participant = SpeedCodingParticipant::findOrFail($id);
        $year = $participant->year;
        $name = $participant->name;
        $participant->delete();

        return redirect()->route('admin.speed_coding.index', ['year' => $year])
            ->with('success', 'Data peserta ' . $name . ' berhasil dihapus.');
    }

    /**
     * Import baris hasil upload/preview file Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'year' => 'required|string|max:10',
            'batch_name' => 'nullable|string|max:50',
            'rows' => 'required|array|min:1',
            'replace_all' => 'nullable|boolean',
        ]);

        $year = $request->year;
        $batchName = $request->batch_name ?? ($year == '2026' ? 'Batch 2' : 'Batch 1');
        $rows = $request->rows;
        $replaceAll = $request->boolean('replace_all', false);

        DB::beginTransaction();
        try {
            if ($replaceAll) {
                SpeedCodingParticipant::where('year', $year)->delete();
            }

            $importedCount = 0;
            foreach ($rows as $index => $row) {
                $name = trim($row['name'] ?? '');
                $school = trim($row['school'] ?? '');
                if (empty($name) || empty($school)) {
                    continue; // Skip baris kosong
                }

                $jenjang = strtoupper(trim($row['jenjang'] ?? 'SMP'));
                if (!in_array($jenjang, ['SD', 'SMP'])) {
                    $jenjang = str_contains(strtoupper($school), 'SD') ? 'SD' : 'SMP';
                }

                $kualifikasiRank = !empty($row['kualifikasi_rank']) ? (int)$row['kualifikasi_rank'] : ($index + 1);
                $finalRank = !empty($row['final_rank']) ? (int)$row['final_rank'] : null;
                $isFinalist = !empty($row['is_finalist']) || ($finalRank !== null && $finalRank <= 10);
                $isWinner = $finalRank !== null && $finalRank <= 3;

                $status = trim($row['status'] ?? '');
                if (empty($status)) {
                    if ($finalRank === 1) $status = 'Juara 1 🥇';
                    elseif ($finalRank === 2) $status = 'Juara 2 🥈';
                    elseif ($finalRank === 3) $status = 'Juara 3 🥉';
                    elseif ($isFinalist) $status = 'Finalis Top 10 🎖️';
                    else $status = 'Peserta Kualifikasi';
                }

                SpeedCodingParticipant::create([
                    'year' => $year,
                    'batch_name' => $batchName,
                    'name' => $name,
                    'school' => $school,
                    'jenjang' => $jenjang,
                    'kelas' => trim($row['kelas'] ?? ($jenjang === 'SD' ? 'Kelas 6 SD' : 'Kelas 8 SMP')),
                    'kualifikasi_rank' => $kualifikasiRank,
                    'kualifikasi_time' => trim($row['kualifikasi_time'] ?? '04:00'),
                    'kualifikasi_score' => !empty($row['kualifikasi_score']) ? (float)$row['kualifikasi_score'] : 90.0,
                    'final_rank' => $finalRank,
                    'final_time' => !empty($row['final_time']) ? trim($row['final_time']) : null,
                    'final_score' => !empty($row['final_score']) ? (float)$row['final_score'] : null,
                    'is_finalist' => $isFinalist,
                    'is_winner' => $isWinner,
                    'badge' => $status,
                    'status' => $status,
                    'language' => 'Scratch 3.0',
                    'avatar' => SpeedCodingParticipant::generateAvatar($name),
                ]);

                $importedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimpor {$importedCount} data peserta untuk Edisi {$year}!",
                'redirect' => route('admin.speed_coding.index', ['year' => $year])
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengimpor data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download berkas template Excel/CSV resmi sesuai format web Handal.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Format_Upload_Speed_Coding_HANDAL.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Tulis UTF-8 BOM agar terbaca sempurna di Microsoft Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom resmi
            fputcsv($handle, [
                'No',
                'Nama Peserta',
                'Asal Sekolah',
                'Jenjang',
                'Kelas',
                'Waktu Kualifikasi',
                'Skor Kualifikasi',
                'Rank Final',
                'Waktu Final',
                'Skor Final',
                'Status'
            ], ';');

            // Baris contoh resmi sesuai format web
            $examples = [
                [1, 'Ahmad Zaki Fauzan', 'SMP Islam Athirah Makassar', 'SMP', 'Kelas 9 SMP', '03:45', '98.0', 1, '03:14', '100.0', 'Juara 1 🥇'],
                [2, 'Zahra Amalia Khairunnisa', 'SD Unggulan BTN Pemda', 'SD', 'Kelas 6 SD', '03:38', '99.0', 2, '03:42', '98.0', 'Juara 2 🥈'],
                [3, 'Siti Nurhaliza', 'SMPN 6 Makassar', 'SMP', 'Kelas 8 SMP', '04:12', '94.0', 3, '04:05', '95.5', 'Juara 3 🥉'],
                [4, 'Muhammad Kenzo Alvaro', 'SD Telkom Makassar', 'SD', 'Kelas 5 SD', '04:35', '92.0', 4, '04:22', '93.0', 'Finalis Top 10 🎖️'],
                [5, 'Nayla Putri Wardani', 'SD Islam Athirah Makassar', 'SD', 'Kelas 6 SD', '04:20', '93.5', 5, '04:38', '91.5', 'Finalis Top 10 🎖️'],
                [6, 'Siswa Peserta Babak 1', 'SMPN 1 Makassar', 'SMP', 'Kelas 7 SMP', '04:50', '88.0', '', '', '', 'Peserta Kualifikasi'],
            ];

            foreach ($examples as $row) {
                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
