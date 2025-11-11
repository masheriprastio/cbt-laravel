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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            // $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->dropForeign(['question_id']);
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->char('selected_option',1)->nullable();
            $table->text('answers_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->foreignId('graded_by')->nullable()->constrained('users');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['test_id','question_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
