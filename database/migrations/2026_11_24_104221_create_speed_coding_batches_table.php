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
        Schema::create('speed_coding_batches', function (Blueprint $table) {
            $table->id();
            $table->string('year')->unique();
            $table->string('batch_name')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('date')->nullable();
            $table->string('location')->default('BBLSDM Komdigi Makassar');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speed_coding_batches');
    }
};
