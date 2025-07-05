<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_file_upload');
        });
    }
    public function down()
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}; 