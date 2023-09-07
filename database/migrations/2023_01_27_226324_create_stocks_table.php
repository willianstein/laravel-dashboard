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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('office_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('addressing_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->unsignedInteger('quantity_min')->default(0);
            $table->unsignedInteger('quantity_max')->default(0);
            $table->string('third_party_system')->nullable();
            $table->string('third_party_system_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('stocks');
    }
};
