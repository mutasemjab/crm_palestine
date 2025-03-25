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
        Schema::create('job_order_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // inside and rellocate home box
            $table->double('price_of_mwaseer')->default(0)->nullable(); // سعر المتر
            $table->double('price_of_trankat')->default(0)->nullable(); // سعر المتر
            $table->double('price_of_brabesh')->default(0)->nullable(); // سعر المتر
            $table->double('price_of_tadkek')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_tadkek_msar_close')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_tarkeeb_router')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_mada_tv')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_le7am_sh3raat')->nullable()->default(0);
            // Entrance and Entrance 2
            $table->double('hawa2e')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('rabt_after_120m')->default(0)->nullable(); // سعر المتر
            $table->double('mwaseer_after_5m')->default(0)->nullable(); // سعر المتر
            $table->double('mawaseer')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('tadkeek')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('tadkek_msar_close')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('tathmena')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_from_engineer')->default(0)->nullable(); // مبلغ مقطوع

            // Sspl in table 1
            $table->double('price_of_1m_per_length')->default(0)->nullable(); // سعر المتر

            // Sspl in table 2
            $table->double('price_of_tarkeb_marwaha')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_one_shara')->default(0)->nullable(); // سعر الشعرة الواحدة

            // Rollout in table 3
            $table->double('price_of_8_12_24')->default(0)->nullable(); // مبلغ مقطوع
            $table->double('price_of_48_72_96_144')->default(0)->nullable(); // مبلغ مقطوع


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
        Schema::dropIfExists('job_order_types');
    }
};
