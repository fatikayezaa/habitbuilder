<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_id')->constrained()->onDelete('cascade');
            $table->date('log_date');
            $table->enum('status', ['completed', 'skipped', 'failed']);
            $table->time('completed_time')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('mood')->nullable(); // Skala 1-5
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};