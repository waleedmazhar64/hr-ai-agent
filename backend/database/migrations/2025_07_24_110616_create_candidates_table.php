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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('resume_path')->nullable();
            $table->float('resume_score')->nullable();
            $table->float('interview_score')->nullable();
            $table->string('decision')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('interview_analyzed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
