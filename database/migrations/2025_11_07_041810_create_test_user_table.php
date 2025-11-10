<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_user', function (Blueprint $table) {
    $table->id();

    // Rekomendasi Laravel 11 (aman & ringkas)
    $table->foreignIdFor(\App\Models\Test::class)
          ->constrained()          // -> references('id')->on('tests')
          ->cascadeOnDelete();

    $table->foreignIdFor(\App\Models\User::class)
          ->constrained()          // -> references('id')->on('users')
          ->cascadeOnDelete();

    $table->enum('status', ['assigned','in_progress','submitted'])->default('assigned');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('finished_at')->nullable();
    $table->unsignedInteger('total_score')->default(0);
    $table->timestamps();

    $table->unique(['test_id','user_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_user');
    }
};
