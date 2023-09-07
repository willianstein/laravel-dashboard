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
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('requester_id')->nullable()->foreign('requester_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('responsible_id')->nullable()->foreign('responsible_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('origin'); /* Solicitante ou Responsavel */
            $table->longText('message');
            $table->string('type')->default('publico'); /* Publica ou Privada */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('ticket_messages');
    }
};
