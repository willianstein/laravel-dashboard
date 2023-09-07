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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requester_id')->foreign('requester_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('responsible_id')->nullable()->foreign('responsible_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->foreign('category_id')->references('id')->on('ticket_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('partner_id')->nullable()->foreign('partner_id')->references('id')->on('partners')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('initial_care_at')->nullable();
            $table->dateTime('ended_in')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tickets');
    }
};
