<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moving_box', function (Blueprint $table) {
            $table->id();
            $table->decimal('balance', 10, 2);
            $table->integer('id_sector');
            $table->integer('id_bank');
            $table->integer('id_cost_center');
            $table->string('status');
            $table->string('responsible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moving_box');
    }
};
