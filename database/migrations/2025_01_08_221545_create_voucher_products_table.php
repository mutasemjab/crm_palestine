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
        Schema::create('voucher_products', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->double('purchasing_price')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('note_voucher_id')->nullable();

            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('note_voucher_id')->references('id')->on('note_vouchers')->onDelete('cascade');

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
        Schema::dropIfExists('voucher_products');
    }
};
