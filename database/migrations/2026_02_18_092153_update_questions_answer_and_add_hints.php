<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateQuestionsAnswerAndAddHints extends Migration
{
    public function up(): void
    {
        // 1. answer → TEXT (თუ ჯერ კიდევ string-ია)
        DB::statement('ALTER TABLE questions MODIFY COLUMN answer TEXT');

        // 2. ძველი string მნიშვნელობები JSON array-ად გადაიყვანება
        DB::table('questions')->get()->each(function ($q) {
            $decoded = json_decode($q->answer, true);
            if (!is_array($decoded)) {
                $answers = array_map('trim', explode(',', $q->answer));
                DB::table('questions')->where('id', $q->id)->update([
                    'answer' => json_encode($answers),
                ]);
            }
        });

        // 3. answer → JSON
        DB::statement('ALTER TABLE questions MODIFY COLUMN answer JSON');

        // 4. hints სვეტის დამატება
        Schema::table('questions', function (Blueprint $table) {
            $table->json('hints')->nullable()->after('answer');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('hints');
        });

        DB::table('questions')->get()->each(function ($q) {
            $answers = json_decode($q->answer, true);
            DB::table('questions')->where('id', $q->id)->update([
                'answer' => is_array($answers) ? implode(', ', $answers) : $q->answer,
            ]);
        });

        DB::statement('ALTER TABLE questions MODIFY COLUMN answer VARCHAR(255)');
    }
}
