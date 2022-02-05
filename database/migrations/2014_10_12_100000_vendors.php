<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Vendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('post_type')->default('vendor');
            $table->enum('status', ['public', 'hide', 'basket'])->default('public');
            $table->string('permalink');
            $table->string('slug')->default('vendor');
            $table->string('title');
            $table->string('thumbnail', 300);
            $table->string('short_desc', 500);
            $table->string('h1');
            $table->string('meta_title');
            $table->string('description');
            $table->string('keywords');
            $table->longText('content');
            $table->integer('lang')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->unique('permalink');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
