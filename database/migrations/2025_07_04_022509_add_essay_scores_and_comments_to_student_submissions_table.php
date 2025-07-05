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
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->json('essay_scores')->nullable();
            $table->json('essay_comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropColumn(['essay_scores', 'essay_comments']);
        });
    }
};
