<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTypeToQuestionsTable extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE questions MODIFY COLUMN question TEXT NULL');
        DB::statement('ALTER TABLE questions MODIFY COLUMN answer TEXT NULL');

        Schema::table('questions', function (Blueprint $table) {
            $table->enum('type', ['question', 'action'])->default('question');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
