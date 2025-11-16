<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Konten metode up() ini sengaja dikosongkan.
        // Logika cascadeOnDelete yang tadinya ada di sini sekarang sudah ditangani
        // dengan benar pada migrasi create_answers_table yang telah diperbaiki.
        // File ini dipertahankan agar histori migrasi tetap konsisten.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Memastikan proses rollback aman jika diperlukan di masa depan.
        if (Schema::hasTable('answers') && Schema::hasColumn('answers', 'question_id')) {
            Schema::table('answers', function (Blueprint $table) {
                // Hapus foreign key yang mungkin ada
                try {
                    // Nama constraint default Laravel: {table}_{column}_foreign
                    $table->dropForeign(['question_id']);
                } catch (\Throwable $e) {
                    // Abaikan error jika constraint tidak ditemukan
                }

                // Tambahkan kembali constraint tanpa cascade untuk mengembalikan ke keadaan semula
                $table->foreign('question_id')->references('id')->on('questions');
            });
        }
    }
};
