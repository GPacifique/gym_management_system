<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('workout_plans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
        $table->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
        $table->string('plan_name');
        $table->text('description')->nullable();
        $table->date('start_date')->nullable();
        $table->date('end_date')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_plans');
    }
};
