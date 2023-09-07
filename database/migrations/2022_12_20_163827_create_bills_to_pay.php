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
        Schema::create('bills_to_pay', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->decimal('value',10,2);
            $table->date('date_competence')->nullable();
            $table->date('date_expire')->nullable();
            $table->integer('id_cost_center');
            $table->integer('id_bank');
            $table->integer('id_favored');
            $table->string('repetition');
            $table->string('status')->default('aberto');
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
        Schema::dropIfExists('bills_to_pay');
    }
};
