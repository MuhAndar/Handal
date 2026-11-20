<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentSurveyQuestion;
use App\Models\School;
use Illuminate\Support\Str;

class StudentSurveyQuestionSeeder extends Seeder
{
    /**
     * Jalankan seeder butir kuesioner skala Likert untuk survei siswa (dari Kuesioner.docx).
     */
    public function run(): void
    {
        // 1. Data butir pertanyaan skala Likert
        $questions = [
            [
                'indicator' => 'Kesadaran Cyber Bullying',
                'question_text' => 'Saya sadar bahwa mengejek, menghina, atau mempermalukan orang lain di media sosial termasuk tindakan cyber bullying.',
                'order' => 1,
            ],
            [
                'indicator' => 'Kesadaran Jejak Digital',
                'question_text' => 'Saya sadar bahwa postingan, foto, atau komentar saya di internet dapat tersimpan dan dilihat kembali oleh orang lain di kemudian hari.',
                'order' => 2,
            ],
            [
                'indicator' => 'Positif Sosial Media',
                'question_text' => 'Saya menggunakan media sosial untuk hal yang bermanfaat (misalnya belajar, berdiskusi, atau mencari informasi positif) dan menghindari penyebaran konten negatif.',
                'order' => 3,
            ],
            [
                'indicator' => 'Interaksi Digital yang Inklusif dan Toleran',
                'question_text' => 'Saat berinteraksi secara online, saya menghargai perbedaan pendapat dan tidak menulis komentar yang merendahkan atau mendiskriminasi kelompok/individu tertentu.',
                'order' => 4,
            ],
            [
                'indicator' => 'Pemahaman Berita Hoaks',
                'question_text' => 'Sebelum membagikan informasi dari media sosial atau grup obrolan, saya mengecek kebenarannya terlebih dahulu dari sumber terpercaya.',
                'order' => 5,
            ],
            [
                'indicator' => 'Pemahaman Berita Hoaks',
                'question_text' => 'Saya dapat mengenali ciri-ciri berita bohong (hoaks), seperti judul yang provokatif, sensasional, atau sumber berita yang tidak jelas.',
                'order' => 6,
            ],
            [
                'indicator' => 'Privasi Digital dan Doxing',
                'question_text' => 'Saya menjaga kerahasiaan data pribadi saya dan tidak membagikan data pribadi orang lain tanpa izin (seperti nomor telepon, foto pribadi, atau alamat rumah).',
                'order' => 7,
            ],
            [
                'indicator' => 'Privasi Digital dan Doxing',
                'question_text' => 'Saya memahami bahwa menyebarkan data pribadi orang lain tanpa izin (doxing) dapat merugikan korban serta memiliki konsekuensi hukum yang serius.',
                'order' => 8,
            ],
        ];

        foreach ($questions as $q) {
            StudentSurveyQuestion::firstOrCreate(
                ['question_text' => $q['question_text']],
                [
                    'indicator' => $q['indicator'],
                    'order' => $q['order'],
                ]
            );
        }

        // 2. Pastikan semua sekolah memiliki kode unik survei siswa
        foreach (School::all() as $school) {
            if (empty($school->survey_code)) {
                $school->survey_code = Str::lower(Str::random(8));
                $school->save();
            }
        }
    }
}
