<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('exercises')) {
            Schema::create('exercises', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->foreignId('topic_id')->constrained()->onDelete('cascade');
                $table->foreignId('subtopic_id')->constrained()->onDelete('cascade');
                $table->foreignId('created_by')->constrained('users');
                $table->boolean('is_file_upload')->default(false);
                $table->string('file_path')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}; 