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
       if (!Schema::hasTable('drivers')){
           Schema::create('drivers', function (Blueprint $table) {
               $table->id();
               $table->unsignedBigInteger('order_id');
               $table->string('driver_name')->nullable();
               $table->string('driver_car_number')->nullable();
               $table->string('driver_phone')->nullable();
               $table->text('google_map')->nullable();
               $table->timestamp('driver_apply_time')->nullable();
               $table->timestamps();
           });
       }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
