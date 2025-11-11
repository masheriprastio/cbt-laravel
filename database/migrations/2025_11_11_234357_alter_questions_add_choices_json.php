<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions','answer_key')) {
                $table->string('answer_key',5)->nullable()->after('score');
            }
            if (!Schema::hasColumn('questions','choices')) {
                $table->json('choices')->nullable()->after('answer_key');
            }
            if (Schema::hasColumn('questions','order') && !Schema::hasColumn('questions','sort_order')) {
                $table->unsignedInteger('sort_order')->nullable()->after('score');
            }
        });

        // opsional: copy 'order' -> 'sort_order' lalu drop 'order'
        if (Schema::hasColumn('questions','order') && Schema::hasColumn('questions','sort_order')) {
            DB::statement('UPDATE questions SET sort_order = `order` WHERE sort_order IS NULL');
            Schema::table('questions', fn(Blueprint $t) => $t->dropColumn('order'));
        }
    }
    public function down(): void {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions','answer_key')) $table->dropColumn('answer_key');
            if (Schema::hasColumn('questions','choices')) $table->dropColumn('choices');
            if (Schema::hasColumn('questions','sort_order')) $table->dropColumn('sort_order');
        });
    }
};

