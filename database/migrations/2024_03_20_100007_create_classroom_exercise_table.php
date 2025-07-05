<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classroom_exercise', function (Blueprint $table) {
            $table->id();
            if (Schema::hasTable('classrooms')) {
                $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            }
            if (Schema::hasTable('exercises')) {
                $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            }
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classroom_exercise');
    }
}; 