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
        Schema::create('order_transport_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('bill_lading')->nullable();      //Conhecimento de Transporte
            $table->string('tag_code')->nullable();         //Codigo de Rastreio
            $table->string('price')->nullable();            //Valor da postagem
            $table->string('delivery_time')->nullable();    //Prazo de Entegra
            $table->string('status')->nullable();           //Status
            $table->longText('metadata')->nullable();       //Metadados em Json
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
        Schema::dropIfExists('order_transport_tags');
    }
};
