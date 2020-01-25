<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_stocks', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->bigIncrements('id');
            $table->string('product_id');
            $table->string('barcode');
            $table->string('product_name');
            $table->string('product_type');
            $table->integer('cp');
            $table->integer('price');
            $table->integer('stock_qty')->comment('both supply and receivings affect this field');
            $table->timestamp('expiry');
            $table->unsignedBigInteger('biz_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id']);

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_stocks');
    }
}
