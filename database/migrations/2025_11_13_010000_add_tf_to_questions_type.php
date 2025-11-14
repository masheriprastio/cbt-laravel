<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alter enum to include 'tf' (True/False)
        if (Schema::hasTable('questions')) {
            DB::statement("ALTER TABLE `questions` MODIFY `type` ENUM('mcq','essay','tf') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('questions')) {
            DB::statement("ALTER TABLE `questions` MODIFY `type` ENUM('mcq','essay') NOT NULL");
        }
    }
};
