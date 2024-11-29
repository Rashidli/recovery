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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
//            customer details
            $table->string('reference_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('vehicle_make')->nullable(); // select
            $table->string('vehicle_model')->nullable(); // select
            $table->string('vehicle_plate_no')->nullable();
            $table->string('time')->nullable();
            $table->string('phone')->nullable();
//            service entry
            $table->string('service_category')->nullable(); // select
            $table->string('gps_coordinates')->nullable();
            $table->string('from_city')->nullable(); // select
            $table->string('from_area')->nullable(); // select
            $table->text('comment')->nullable();
            $table->string('service_type')->nullable(); // select
            $table->string('to_location_details')->nullable(); // select
            $table->string('to_city')->nullable(); // select
            $table->string('to_area')->nullable(); // select
//            trip details
            $table->string('trip_number')->nullable();
            $table->string('starting_time')->nullable();
            $table->string('starting_km')->nullable();
            $table->string('estimated_amt')->nullable();
            $table->string('reached_time')->nullable();
            $table->string('reached_km')->nullable();
            $table->string('ending_time')->nullable();
            $table->string('ending_km')->nullable();
//            driver details
            $table->string('driver_name')->nullable();
            $table->string('driver_car_number')->nullable();
            $table->string('driver_phone')->nullable();
            $table->text('google_map')->nullable();
            $table->timestamp('driver_apply_time')->nullable();
//            files
            $table->string('file')->nullable(); // this can be image and other files like pdf
            $table->enum('status', ['new', 'accepted','in_progress', 'canceled', 'completed'])
                ->default('new');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
