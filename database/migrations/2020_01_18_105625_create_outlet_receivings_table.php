<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_receivings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('or_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('old_qty');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('total_price');
            $table->integer('cp');
            $table->integer('total_cp');
            $table->timestamp('expiry');
            $table->enum('checkout',[1,0])->default(1);
            $table->unsignedBigInteger('ors_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet','ors_id','product_id'],'outlet_receivings_index');

             // relations
             $table->foreign('created_by')
                 ->references('id')
                 ->on('outlet_admin')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
 
             $table->foreign('biz_id')
                 ->references('id')
                 ->on('businesses')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('outlet')
                 ->references('id')
                 ->on('outlets')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('ors_id')
                 ->references('id')
                 ->on('outlet_receivings_sum')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('product_id')
                 ->references('id')
                 ->on('outlet_stocks')
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
        Schema::dropIfExists('outlet_receivings');
    }
}
