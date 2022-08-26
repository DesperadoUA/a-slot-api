<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CasinoMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('casino_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('faq');
            $table->integer('rating');
            $table->string('ref');
            $table->string('licenses');
            $table->string('exchange');
            $table->string('events');
            $table->string('min_deposit');
            $table->string('min_payout');
            $table->longText('slot_category');
            $table->longText('payment_methods');
            $table->longText('payment_out_methods');
            $table->longText('active_languages');
            $table->integer('convenience');
            $table->integer('replenishment');
            $table->integer('support');
            $table->integer('actions');
            $table->unique('post_id');
            $table->foreign('post_id')
                  ->references('id')
                  ->on('casinos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('casino_meta');
    }
}
