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
        Schema::create('partner_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('price', '8', '2');
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('partner_services');
    }
};
