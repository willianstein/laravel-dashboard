<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('modality');
            $table->string('carrier_name')->nullable();
            $table->string('packaging')->nullable();
            $table->string('driver')->nullable();
            $table->string('driver_document')->nullable();
            $table->string('car_model')->nullable();
            $table->string('car_type')->nullable();
            $table->string('car_plate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('transports');
    }
};
