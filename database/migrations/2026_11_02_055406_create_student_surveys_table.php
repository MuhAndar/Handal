<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk tabel survei siswa dan kolom skor komposit sekolah.
     */
    public function up(): void
    {
        // 1. Tambah kolom survei siswa dan pemisahan skor pada tabel schools
        Schema::table('schools', function (Blueprint $table) {
            $table->string('survey_code')->nullable()->unique()->after('npsn');
            $table->boolean('is_student_survey_open')->default(true)->after('survey_code');
            $table->decimal('self_assessment_score', 8, 2)->default(0)->after('is_student_survey_open');
            $table->decimal('student_survey_score', 8, 2)->default(0)->after('self_assessment_score');
            $table->unsignedInteger('student_respondents_count')->default(0)->after('student_survey_score');
        });

        // 2. Tabel pertanyaan survei siswa (Skala Likert)
        Schema::create('student_survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string('indicator'); // Indikator (misal: Kesadaran Cyber Bullying)
            $table->text('question_text'); // Pernyataan survei
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });

        // 3. Tabel respon survei dari siswa
        Schema::create('student_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('student_name')->nullable(); // Opsional / Anonim
            $table->string('class_grade')->nullable(); // Misal: X, XI, XII
            $table->decimal('score', 8, 2)->default(0); // Nilai individu (skala 0-100)
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 4. Tabel rincian jawaban per pertanyaan siswa
        Schema::create('student_survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('student_survey_responses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('student_survey_questions')->onDelete('cascade');
            $table->unsignedTinyInteger('answer_value'); // 1: STS, 2: TS, 3: S, 4: SS
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_survey_answers');
        Schema::dropIfExists('student_survey_responses');
        Schema::dropIfExists('student_survey_questions');

        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'survey_code',
                'is_student_survey_open',
                'self_assessment_score',
                'student_survey_score',
                'student_respondents_count',
            ]);
        });
    }
};
