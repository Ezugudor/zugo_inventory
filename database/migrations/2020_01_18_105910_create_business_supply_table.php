<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSupplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_supply', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('bs_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('cp');
            $table->integer('total_price');
            $table->integer('total_cp');
            $table->integer('discount');
            $table->unsignedBigInteger('bss_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'bss_id', 'product_id'], 'business_supply_index');

            // relations
            $table->foreign('created_by')
                ->references('id')
                ->on('business_admin')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('biz_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('bss_id')
                ->references('id')
                ->on('business_supply_sum')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('business_stocks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_supply');
    }
}
