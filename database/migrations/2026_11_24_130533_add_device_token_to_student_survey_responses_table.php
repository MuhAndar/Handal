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
        Schema::table('student_survey_responses', function (Blueprint $table) {
            $table->string('device_token', 64)->nullable()->index()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_survey_responses', function (Blueprint $table) {
            $table->dropColumn('device_token');
        });
    }
};
