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
        Schema::create('gym_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $table->integer('capacity')->default(20);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('days_of_week'); // Stored as comma-separated values (e.g., "1,3,5" for Mon,Wed,Fri)
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->enum('status', ['active', 'cancelled', 'full'])->default('active');
            $table->timestamps();
            $table->softDeletes(); // For archiving classes instead of deleting them
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_classes');
    }
};