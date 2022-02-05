<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BettingMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('betting_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->longText('faq');
            $table->integer('rating');
            $table->string('ref');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('bettings')
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
        Schema::dropIfExists('betting_meta');
    }
}
