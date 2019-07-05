<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeckillProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->comment('对应商品的id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->dateTime('start_at')->comment('秒杀开始时间');
            $table->dateTime('end_at')->comment('秒杀结束时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seckill_products');
    }
}
