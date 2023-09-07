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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('office_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('transport_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('invoice')->nullable();
            $table->string('content_declaration')->nullable();
            $table->string('status');
            $table->date('forecast')->nullable();
            $table->string('third_system')->nullable();
            $table->string('third_system_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }
};
