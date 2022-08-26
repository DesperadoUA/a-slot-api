<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShareMeta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_meta', function (Blueprint $table) {
            $table->bigInteger('post_id')->unsigned();
            $table->text('faq');
            $table->unique('post_id');
            $table->foreign('post_id')
                ->references('id')
                ->on('shares')
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
        Schema::dropIfExists('share_meta');
    }
}
