<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('default_gym_id')->nullable()->after('role')->constrained('gyms')->nullOnDelete();
        });

        Schema::create('gym_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained('gyms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->timestamps();
            $table->unique(['gym_id','user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_user');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('default_gym_id');
        });
    }
};
