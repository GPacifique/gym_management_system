<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'gym_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('gym_id')->nullable()->after('id')->constrained('gyms')->onDelete('cascade');
                $table->index('gym_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'gym_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['gym_id']);
                $table->dropIndex(['gym_id']);
                $table->dropColumn('gym_id');
            });
        }
    }
};
