<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'class')) {
                $table->string('class')->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'room')) {
                $table->string('room')->nullable()->after('class');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','room')) $table->dropColumn('room');
            if (Schema::hasColumn('users','class')) $table->dropColumn('class');
            if (Schema::hasColumn('users','username')) $table->dropColumn('username');
        });
    }
};
