<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // jika belum ada, tambahkan kolom untuk MCQ
            if (!Schema::hasColumn('questions', 'answer_key')) {
                $table->string('answer_key', 5)->nullable()->after('score'); // mis. "A","B","C","D","E"
            }
            if (!Schema::hasColumn('questions', 'choices')) {
                $table->json('choices')->nullable()->after('answer_key');    // simpan opsi A-E
            }

            // kolom 'order' bisa bikin masalah, ganti ke 'sort_order'
            if (Schema::hasColumn('questions', 'order') && !Schema::hasColumn('questions', 'sort_order')) {
                $table->unsignedInteger('sort_order')->nullable()->after('score');
            }
        });

        // copy nilai lama dari `order` ke `sort_order` (kalau ada data)
        if (Schema::hasColumn('questions', 'order') && Schema::hasColumn('questions', 'sort_order')) {
            DB::statement('UPDATE questions SET sort_order = `order` WHERE sort_order IS NULL');
            // opsional: drop kolom order kalau sudah aman
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions', 'answer_key')) {
                $table->dropColumn('answer_key');
            }
            if (Schema::hasColumn('questions', 'choices')) {
                $table->dropColumn('choices');
            }
            // tidak mengembalikan 'order' agar aman
            if (Schema::hasColumn('questions', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });
    }
};
