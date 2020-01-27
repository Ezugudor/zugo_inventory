<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletSalesSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_sales_sum', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('oss_id');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->string('invoice', 100);
            $table->enum('payment_method', ['part', 'full', 'none'])->default('full');
            $table->unsignedBigInteger('customer');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'customer', 'outlet']);

            // relations
            $table->foreign('created_by')
                ->references('id')
                ->on('outlet_admin')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('customer')
                ->references('id')
                ->on('customer_business')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('outlet')
                ->references('id')
                ->on('outlets')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('biz_id')
                ->references('id')
                ->on('businesses')
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
        Schema::dropIfExists('outlet_sales_sum');
    }
}
