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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('isbn', 13)->unique();
            $table->string('title');
            $table->string('publisher')->nullable();
            $table->string('category')->nullable();
            $table->longText('synopsis')->nullable();
            $table->integer('height');
            $table->integer('width');
            $table->integer('length');
            $table->integer('weight');
            $table->string('cover')->nullable();
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
        Schema::dropIfExists('products');
    }
};
