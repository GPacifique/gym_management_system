<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

 public function up():void
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
        $table->string('plan_name');
        $table->decimal('price', 8, 2);
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('status', ['active', 'expired', 'pending'])->default('active');
        $table->timestamps()
    ;
    });
}   
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
