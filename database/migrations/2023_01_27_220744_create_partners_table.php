<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Partners;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trade_name')->nullable();
            $table->string('person')->nullable();
            $table->string('document01')->unique();
            $table->string('document02')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', array_keys(Partners::TYPES))->default('Cliente');
            $table->integer('segment')->nullable();
            $table->longText('obs')->nullable();
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
        Schema::dropIfExists('partners');
    }
};
