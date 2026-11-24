<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountRejected;
use App\Models\Survey;
use App\Models\SurveyAnswer;
use App\Models\SurveyCategory;
use Illuminate\Support\Facades\Auth;

class ValidatorController extends Controller
{
    public function index()
    {
        $registrations = Registration::where('status', 'pending')->latest()->get();

        $submittedSurveys = Survey::with('school')
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('validator.dashboard', compact('registrations', 'submittedSurveys'));
    }

    public function verifySurvey($id)
    {
        $survey = Survey::with('school')->findOrFail($id);
        $categories = SurveyCategory::with(['questions.options'])->get();
        $answers = SurveyAnswer::where('survey_id', $id)
            ->get()
            ->keyBy('question_id');

        return view('validator.verify', compact('survey', 'categories', 'answers'));
    }

    public function storeVerification(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $validationData = $request->input('validation', []);
        $currentAnswers = SurveyAnswer::where('survey_id', $id)->get()->keyBy('question_id');

        // Validasi 1: Jika validator mengubah nilai/opsi dari pilihan sekolah, WAJIB mengisi catatan alasan
        foreach ($validationData as $questionId => $data) {
            $newAnswerId = $data['answer_id'] ?? null;
            $note = trim($data['note'] ?? '');
            $current = $currentAnswers[$questionId] ?? null;

            if ($current && $newAnswerId && $current->answer_value != $newAnswerId && empty($note)) {
                return back()->with('error', 'Anda mengubah opsi penilaian pada salah satu pertanyaan tanpa menyertakan catatan. Wajib mengisi alasan koreksi.');
            }
        }

        if ($request->action === 'reject') {
            // Validasi 2: Minta revisi hanya dapat dilakukan jika ada minimal 1 catatan koreksi
            $hasAnyNote = false;
            foreach ($validationData as $questionId => $data) {
                if (!empty(trim($data['note'] ?? ''))) {
                    $hasAnyNote = true;
                    break;
                }
            }

            if (!$hasAnyNote) {
                return back()->with('error', 'Revisi tidak dapat dikirim karena belum ada catatan koreksi. Harap tuliskan minimal 1 catatan perbaikan untuk sekolah.');
            }

            foreach ($validationData as $questionId => $data) {
                $note = !empty(trim($data['note'] ?? '')) ? trim($data['note']) : null;
                $newAnswerId = $data['answer_id'] ?? null;
                $updatePayload = [
                    'validator_note' => $note,
                    'is_revised'     => false,
                ];
                if ($newAnswerId) {
                    $updatePayload['answer_value'] = $newAnswerId;
                }

                SurveyAnswer::updateOrCreate(
                    [
                        'survey_id'   => $survey->id,
                        'question_id' => $questionId
                    ],
                    $updatePayload
                );
            }

            $survey->update(['status' => 'rejected']);
            if ($survey->school) {
                $survey->school->recalculateOverallScore();
            }

            return redirect()->route('validator.dashboard')
                ->with('warning', 'Asesmen dikembalikan ke sekolah untuk perbaikan dengan catatan revisi.');
        } else {
            foreach ($validationData as $questionId => $data) {
                $newAnswerId = $data['answer_id'] ?? null;
                $note        = !empty(trim($data['note'] ?? '')) ? trim($data['note']) : null;
                if ($newAnswerId) {
                    SurveyAnswer::updateOrCreate(
                        [
                            'survey_id'   => $survey->id,
                            'question_id' => $questionId
                        ],
                        [
                            'answer_value'   => $newAnswerId,
                            'validator_note' => $note
                        ]
                    );
                }
            }

            $survey->update([
                'status' => 'verified',
                'validator_id' => Auth::id()
            ]);
            $this->recalculateScore($survey);

            return redirect()->route('validator.dashboard')
                ->with('success', 'Asesmen berhasil diverifikasi. Skor sekolah telah diperbarui.');
        }
    }
    private function recalculateScore(Survey $survey)
    {
        $totalObtainedScore = 0;
        $maxPossibleScore = 0;

        $answers = SurveyAnswer::where('survey_id', $survey->id)->get()->keyBy('question_id');
        $categories = SurveyCategory::with('questions.options')->get();

        foreach ($categories as $category) {
            foreach ($category->questions as $question) {
                $maxQuestionScore = $question->options->max('score_value');
                if (!$maxQuestionScore) continue;
                $maxPossibleScore += $maxQuestionScore;

                if (isset($answers[$question->id])) {
                    $selectedOptionId = $answers[$question->id]->answer_value;
                    $option = $question->options->where('id', $selectedOptionId)->first();
                    if ($option) {
                        $totalObtainedScore += $option->score_value;
                    }
                }
            }
        }

        $finalScore = ($maxPossibleScore > 0) ? ($totalObtainedScore / $maxPossibleScore) * 100 : 0;
        $finalScore = round($finalScore, 2);

        $survey->update(['total_score' => $finalScore]);
        if ($survey->school) {
            $survey->school->self_assessment_score = $finalScore;
            $survey->school->recalculateOverallScore();
        }

        \App\Http\Controllers\LandingController::clearLandingCache();
    }

    public function show($id)
    {
        $registration = Registration::findOrFail($id);
        return view('validator.show', compact('registration'));
    }
    public function approve(Request $request, $id)
{
    $reg = Registration::findOrFail($id);

    $reg->update([
        'status' => 'verified',
    ]);

    return redirect()->route('validator.dashboard')
        ->with('success', 'Pendaftaran sekolah berhasil diverifikasi. Akun siap dibuat oleh Admin.');
}

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $reg = Registration::findOrFail($id);

        $reg->update([
            'status' => 'rejected',
            'admin_notes' => $request->reason
        ]);

        try {
            Mail::to($reg->email)->send(new AccountRejected($reg, $request->reason));
        } catch (\Exception $e) {
            return redirect()->route('validator.dashboard')
                ->with('warning', 'Ditolak, tapi email notifikasi gagal terkirim.');
        }

        return redirect()->route('validator.dashboard')->with('success', 'Pendaftaran ditolak.');
    }
}
