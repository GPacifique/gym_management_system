<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('members', 'chip_id')) {
            Schema::table('members', function (Blueprint $table) {
                $table->string('chip_id', 191)->nullable()->unique()->after('photo_path');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('members', 'chip_id')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropUnique(['chip_id']);
                $table->dropColumn('chip_id');
            });
        }
    }
};
