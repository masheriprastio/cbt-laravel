<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','class')) {
                $table->dropColumn('class');
            }
            if (Schema::hasColumn('users','room')) {
                $table->dropColumn('room');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users','class')) {
                $table->string('class')->nullable();
            }
            if (! Schema::hasColumn('users','room')) {
                $table->string('room')->nullable();
            }
        });
    }
};
