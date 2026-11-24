<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SurveyController extends Controller
{
    public function start()
    {
        $user = Auth::user();
        if (!$user->school) return back()->with('error', 'Profil sekolah belum ada.');
        $existingSurvey = Survey::where('school_id', $user->school->id)
            ->where('year', date('Y'))
            ->first();

        if ($existingSurvey && !in_array($existingSurvey->status, ['draft', 'rejected'])) {
            return redirect()->route('school.dashboard')
                ->with('error', 'Asesmen Anda sedang dalam proses verifikasi atau telah selesai.');
        }
        $survey = Survey::firstOrCreate(
            ['school_id' => $user->school->id, 'year' => date('Y')],
            ['status' => 'draft', 'total_score' => 0]
        );
        if ($survey->status === 'submitted') {
            return redirect()->route('school.dashboard');
        }

        return redirect()->route('school.survey.step', 1);
    }

    // 2. Menampilkan Pertanyaan Per Kategori (Step)
    public function step($stepNumber)
    {
        $user = Auth::user();
        $survey = Survey::where('school_id', $user->school->id)
            ->where('year', date('Y'))
            ->first();
        if ($survey && !in_array($survey->status, ['draft', 'rejected'])) {
            return redirect()->route('school.dashboard')
                ->with('error', 'Akses ditolak. Survei sedang dikunci.');
        }

        $categories = SurveyCategory::with('questions')->orderBy('id')->get();
        if ($stepNumber < 1 || $stepNumber > $categories->count()) {
            return redirect()->route('school.dashboard');
        }

        $currentCategory = $categories[$stepNumber - 1];
        $currentCategory->load('questions.options');

        // KUNCI AGAR JAWABAN TIDAK HILANG
        $existingAnswers = SurveyAnswer::where('survey_id', $survey->id ?? 0)
            ->get()
            ->keyBy('question_id');

        return view('school.survey.wizard', [
            'currentStep' => $stepNumber,
            'totalSteps' => $categories->count(),
            'category' => $currentCategory,
            'allCategories' => $categories,
            'questions' => $currentCategory->questions,
            'existingAnswers' => $existingAnswers
        ]);
    }
    public function process(Request $request, $stepNumber)
    {
        $user = Auth::user();

        // 1. Cari data survei draft/rejected milik sekolah ini
        $survey = Survey::where('school_id', $user->school->id)
            ->where('year', date('Y'))
            ->whereIn('status', ['draft', 'rejected'])
            ->first();
        if (!$survey) {
            return redirect()->route('school.dashboard')
                ->with('error', 'Sesi survei tidak ditemukan atau sudah dikunci.');
        }
        if ($request->has('answers')) {
            foreach ($request->answers as $questionId => $optionId) {
                $existingAnswer = SurveyAnswer::where('survey_id', $survey->id)
                    ->where('question_id', $questionId)
                    ->first();

                $noteToSave = $existingAnswer ? $existingAnswer->validator_note : null;
                $isRevised = $existingAnswer ? (bool)$existingAnswer->is_revised : false;

                // Jika soal memiliki catatan validator dan sekolah mengubah jawabannya:
                if (!empty($noteToSave)) {
                    if ($existingAnswer && $existingAnswer->answer_value != $optionId) {
                        $isRevised = true;
                    }
                }

                SurveyAnswer::updateOrCreate(
                    [
                        'survey_id'   => $survey->id,
                        'question_id' => $questionId
                    ],
                    [
                        'answer_value'   => $optionId,
                        'validator_note' => $noteToSave,
                        'is_revised'     => $isRevised,
                    ]
                );
            }
        }

        // 3. Logika Navigasi & Pengumpulan Langsung
        if ($request->input('action') === 'submit_final' || $request->has('submit_final')) {
            $totalQuestionsCount = \App\Models\SurveyQuestion::count();
            $answeredCount = SurveyAnswer::where('survey_id', $survey->id)
                ->whereNotNull('answer_value')
                ->count();

            if ($answeredCount < $totalQuestionsCount) {
                $unansweredCount = $totalQuestionsCount - $answeredCount;
                return redirect()->route('school.survey.step', $stepNumber)
                    ->with('error', "Belum dapat mengumpulkan: Masih ada {$unansweredCount} pertanyaan yang belum Anda jawab. Silakan lengkapi seluruh kategori.");
            }

            $this->calculateFinalScore($survey);
            return redirect()->route('school.dashboard')
                ->with('success', 'Asesmen berhasil dikumpulkan secara lengkap! Menunggu verifikasi dari tim validator.');
        }

        // Jika pengguna memilih lompat langsung ke kategori tertentu
        if ($request->filled('jump_to_step')) {
            $targetStep = (int) $request->jump_to_step;
            $totalSteps = SurveyCategory::count();
            if ($targetStep >= 1 && $targetStep <= $totalSteps) {
                return redirect()->route('school.survey.step', $targetStep);
            }
        }

        $totalSteps = SurveyCategory::count();

        if ($stepNumber < $totalSteps) {
            return redirect()->route('school.survey.step', $stepNumber + 1);
        } else {
            $this->calculateFinalScore($survey);
            return redirect()->route('school.dashboard')
                ->with('success', 'Asesmen berhasil disubmit! Menunggu verifikasi dari tim validator.');
        }
    }

    // 4. Logika Hitung Skor Asesmen Mandiri (Optimasi Query & Pembobotan 70%-30%)
    private function calculateFinalScore(Survey $survey)
    {
        $totalObtainedScore = 0;
        $maxPossibleScore = 0;
        $categories = SurveyCategory::with('questions.options')->get();

        // Ambil semua jawaban dalam 1 kueri terindeks (mencegah N+1 query)
        $answers = SurveyAnswer::where('survey_id', $survey->id)->get()->keyBy('question_id');

        foreach ($categories as $category) {
            foreach ($category->questions as $question) {
                $maxQuestionScore = $question->options->max('score_value');

                if (!$maxQuestionScore) continue;

                $maxPossibleScore += $maxQuestionScore;

                if (isset($answers[$question->id])) {
                    $selectedOption = $question->options->where('id', $answers[$question->id]->answer_value)->first();
                    if ($selectedOption) {
                        $totalObtainedScore += $selectedOption->score_value;
                    }
                }
            }
        }

        $selfScore = ($maxPossibleScore > 0) ? ($totalObtainedScore / $maxPossibleScore) * 100 : 0;
        $selfScore = round($selfScore, 2);

        $survey->update([
            'total_score' => $selfScore,
            'status' => 'submitted'
        ]);

        if ($survey->school) {
            // Skor resmi hanya akan dipublikasikan setelah disetujui validator
            $survey->school->recalculateOverallScore();
        }

        \App\Http\Controllers\LandingController::clearLandingCache();
    }

    // 5. Menampilkan Hasil (Sudah Diperbaiki Keamanannya)
    public function result($id)
    {
        $user = Auth::user();

        if (!$user->school) {
            return redirect()->route('school.dashboard')->with('error', 'Profil sekolah tidak ditemukan.');
        }

        // KODE FINAL: Wajib filter dengan ID Sekolah yang sedang login
        $survey = Survey::with(['answers.question.options'])
            ->where('school_id', $user->school->id) 
            ->where('id', $id) 
            ->firstOrFail();

        return view('school.survey.result', compact('survey'));
    }
}