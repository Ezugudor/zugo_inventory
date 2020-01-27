<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_receivings', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('br_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('old_qty');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('total_price');
            $table->integer('cp');
            $table->integer('total_cp');
            $table->timestamp('expiry');
            $table->enum('confirmed',[1,0])->default(1)->comment('may not be necessary');
            $table->unsignedBigInteger('brs_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','brs_id','product_id'],'business_receivings_index');

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

             $table->foreign('brs_id')
                 ->references('id')
                 ->on('business_receivings_sum')
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
        Schema::dropIfExists('business_receivings');
    }
}
