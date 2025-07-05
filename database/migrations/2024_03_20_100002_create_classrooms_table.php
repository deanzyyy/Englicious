<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('password');
            $table->softDeletes();
            $table->timestamps();

            $table->index('name');
            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}; 