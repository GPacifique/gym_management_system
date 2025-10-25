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
        Schema::table('gyms', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->string('website')->nullable()->after('email');
            $table->text('description')->nullable()->after('website');
            $table->text('opening_hours')->nullable()->after('description');
            $table->string('logo')->nullable()->after('opening_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn(['email', 'website', 'description', 'opening_hours', 'logo']);
        });
    }
};
