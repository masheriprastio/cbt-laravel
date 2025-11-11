<?php

// database/migrations/xxxx_alter_fk_cascade.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // questions.test_id -> tests.id (cascade)
        if (Schema::hasTable('questions') && Schema::hasColumn('questions','test_id')) {
            Schema::table('questions', function (Blueprint $table) {
                // drop FK lama (nama FK bisa berbeda; aman pakai Doctrine loop jika perlu)
                try { $table->dropForeign(['test_id']); } catch (\Throwable $e) {}
                $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
            });
        }
        // answers.question_id -> questions.id (cascade)
        if (Schema::hasTable('answers') && Schema::hasColumn('answers','question_id')) {
            Schema::table('answers', function (Blueprint $table) {
                try { $table->dropForeign(['question_id']); } catch (\Throwable $e) {}
                $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            });
        }
    }
    public function down(): void {}
};

