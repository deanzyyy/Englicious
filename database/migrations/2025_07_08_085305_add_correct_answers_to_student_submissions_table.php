<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->integer('correct_answers')->nullable()->after('answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('student_submissions', function (Blueprint $table) {
            $table->dropColumn('correct_answers');
        });
    }
};
