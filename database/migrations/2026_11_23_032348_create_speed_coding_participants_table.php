<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('speed_coding_participants', function (Blueprint $table) {
            $table->id();
            $table->string('year')->default('2026')->index();
            $table->string('batch_name')->nullable()->default('Batch 2');
            $table->integer('kualifikasi_rank')->nullable()->default(1);
            $table->integer('final_rank')->nullable();
            $table->string('name');
            $table->string('school');
            $table->string('kelas')->nullable();
            $table->enum('jenjang', ['SD', 'SMP'])->default('SMP');
            $table->string('kualifikasi_time')->nullable(); // Waktu pengerjaan babak kualifikasi (mm:ss)
            $table->decimal('kualifikasi_score', 5, 2)->nullable()->default(0); // Skor akurasi kualifikasi
            $table->string('final_time')->nullable(); // Waktu pengerjaan babak final (mm:ss)
            $table->decimal('final_score', 5, 2)->nullable()->default(0); // Skor akurasi babak final
            $table->boolean('is_finalist')->default(false);
            $table->boolean('is_winner')->default(false);
            $table->string('badge')->nullable(); // misal: Juara 1 (Emas), Juara 2 (Perak), dll.
            $table->string('status')->nullable(); // misal: Juara 1 🥇, Lolos ke Final (Top 10), Peserta Kualifikasi
            $table->string('language')->default('Scratch 3.0');
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speed_coding_participants');
    }
};
