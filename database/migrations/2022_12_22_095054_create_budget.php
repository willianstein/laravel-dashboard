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
        Schema::create('budget', function (Blueprint $table) {
            $table->id();
            $table->string('objective');
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->dateTime('date_conclusion');
            $table->string('status')->default('Em Análise');
            $table->integer('partner_id');
            $table->decimal('value',10,2);
            $table->string('pdf')->nullable();
            $table->boolean('purchase_order')->default(false);
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
        Schema::dropIfExists('budget');
    }
};
