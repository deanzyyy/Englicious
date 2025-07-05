<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->json('options')->nullable()->change();
            $table->integer('correct_answer')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->json('options')->nullable(false)->change();
            $table->integer('correct_answer')->nullable(false)->change();
        });
    }
}; 