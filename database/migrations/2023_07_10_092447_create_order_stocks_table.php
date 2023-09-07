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
        Schema::create('order_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('separate_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_stocks');
    }
};
