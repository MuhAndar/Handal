<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\StudentSurveyQuestion;
use App\Models\StudentSurveyResponse;
use App\Models\StudentSurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class StudentSurveyController extends Controller
{
    /**
     * Tampilkan formulir survei untuk siswa berdasarkan kode unik sekolah.
     */
    public function show(Request $request, $code)
    {
        $school = School::where('survey_code', $code)->firstOrFail();

        // Cek apakah survei sedang dibuka oleh pihak sekolah
        if (!$school->is_student_survey_open) {
            return view('student_survey.closed', compact('school'));
        }

        $questions = StudentSurveyQuestion::orderBy('order')->get();
        $cookieName = 'survey_submitted_' . $school->id;
        $hasSubmitted = (bool) $request->cookie($cookieName);

        return view('student_survey.show', compact('school', 'questions', 'hasSubmitted'));
    }

    /**
     * Simpan jawaban survei siswa dan hitung skor otomatis.
     */
    public function store(Request $request, $code)
    {
        $school = School::where('survey_code', $code)->firstOrFail();

        if (!$school->is_student_survey_open) {
            return redirect()->route('student.survey.show', $code)
                ->with('error', 'Survei untuk sekolah ini saat ini sedang ditutup.');
        }

        $cookieName = 'survey_submitted_' . $school->id;

        // 1. Validasi Cookie Browser (Mencegah pengiriman berulang dari device yang sama)
        if ($request->cookie($cookieName)) {
            return redirect()->route('student.survey.show', $code)
                ->with('error', 'Perangkat ini sudah pernah mengirimkan tanggapan survei untuk sekolah ini.');
        }

        // 2. Validasi Device Fingerprint Token (Mencegah pengiriman berulang meski cookie dibersihkan)
        $deviceToken = $request->input('device_token');
        if (!empty($deviceToken)) {
            $alreadySubmitted = StudentSurveyResponse::where('school_id', $school->id)
                ->where('device_token', $deviceToken)
                ->exists();
            if ($alreadySubmitted) {
                return redirect()->route('student.survey.show', $code)
                    ->with('error', 'Perangkat Anda terdeteksi sudah pernah mengisi survei untuk sekolah ini.')
                    ->withCookie(cookie()->forever($cookieName, '1'));
            }
        }

        $questions = StudentSurveyQuestion::orderBy('order')->get();

        // Siapkan aturan validasi dinamis untuk setiap pertanyaan
        $rules = [
            'student_name' => 'nullable|string|max:100',
            'class_grade'  => 'required|string|max:50',
            'answers'      => 'required|array|size:' . $questions->count(),
            'answers.*'    => 'required|integer|between:1,4',
        ];

        $messages = [
            'class_grade.required' => 'Silakan pilih atau isi kelas Anda.',
            'answers.required'     => 'Semua butir pertanyaan wajib dijawab.',
            'answers.size'         => 'Pastikan seluruh pertanyaan telah terisi lengkap.',
            'answers.*.between'    => 'Pilihan jawaban tidak valid.',
        ];

        $request->validate($rules, $messages);

        DB::transaction(function () use ($request, $school, $questions, $deviceToken) {
            $totalPoints = 0;
            $maxPoints = $questions->count() * 4; // Skala Likert 1-4 (max per soal = 4)

            foreach ($request->answers as $questionId => $value) {
                $totalPoints += (int) $value;
            }

            // Normalisasi skor siswa ke skala 0 - 100
            $normalizedScore = ($maxPoints > 0) ? ($totalPoints / $maxPoints) * 100 : 0;

            // 1. Simpan data respon siswa
            $response = StudentSurveyResponse::create([
                'school_id'    => $school->id,
                'student_name' => $request->student_name ?: 'Anonim',
                'class_grade'  => $request->class_grade,
                'score'        => round($normalizedScore, 2),
                'ip_address'   => $request->ip(),
                'device_token' => $deviceToken ?: hash('sha256', $request->ip() . ($request->userAgent() ?? '')),
            ]);

            // 2. Simpan rincian per butir jawaban
            foreach ($request->answers as $questionId => $value) {
                StudentSurveyAnswer::create([
                    'response_id'  => $response->id,
                    'question_id'  => $questionId,
                    'answer_value' => (int) $value,
                ]);
            }

            // 3. Hitung ulang rata-rata skor survei siswa dan skor komposit sekolah (70% - 30%)
            $school->recalculateOverallScore();

            // 4. Bersihkan cache peringkat publik agar data langsung terbarukan
            \App\Http\Controllers\LandingController::clearLandingCache();
        });

        $cookieName = 'survey_submitted_' . $school->id;

        return response()
            ->view('student_survey.thank_you', compact('school'))
            ->withCookie(cookie()->forever($cookieName, '1'));
    }

    /**
     * Buka atau tutup survei siswa (Akses khusus akun Sekolah).
     */
    public function toggleStatus(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->school) {
            return back()->with('error', 'Akses ditolak.');
        }

        $school = $user->school;
        $school->is_student_survey_open = !$school->is_student_survey_open;
        $school->save();

        $statusText = $school->is_student_survey_open ? 'dibuka' : 'ditutup';

        return back()->with('success', "Survei literasi siswa berhasil {$statusText}.");
    }

    /**
     * Buat ulang kode tautan survei jika dibutuhkan (Akses khusus akun Sekolah).
     */
    public function regenerateCode(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->school) {
            return back()->with('error', 'Akses ditolak.');
        }

        $school = $user->school;
        $school->survey_code = strtolower(\Illuminate\Support\Str::random(8));
        $school->save();

        return back()->with('success', 'Tautan baru survei siswa berhasil diterbitkan.');
    }
}
