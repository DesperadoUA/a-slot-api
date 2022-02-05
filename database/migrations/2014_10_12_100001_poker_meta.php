<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PokerMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poker_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('faq');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('pokers')
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
        Schema::dropIfExists('poker_meta');
    }
}
