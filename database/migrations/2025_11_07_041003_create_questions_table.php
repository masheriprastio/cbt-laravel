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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')
      ->constrained('tests')   // <- plural: 'tests'
      ->cascadeOnDelete();
            $table->enum('type', ['mcq', 'essay'])->index();
            $table->text('text');
            $table->string('answers_key')->nullable();
            $table->unsignedInteger('score')->default(1);
            $table->unsignedInteger('order')->default(1);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
