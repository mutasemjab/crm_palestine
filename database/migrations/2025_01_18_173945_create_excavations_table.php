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
        Schema::create('excavations', function (Blueprint $table) {
            $table->id();
            $table->text('name_of_worker')->nullable();
            $table->text('expenses')->nullable();
            $table->text('purchases')->nullable();
            $table->text('note')->nullable();
            $table->text('google_drive_link')->nullable();
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
        Schema::dropIfExists('excavations');
    }
};
