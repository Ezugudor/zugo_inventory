<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletReceivingsSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_receivings_sum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ors_id');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->integer('driver');
            $table->integer('truck_id');
            $table->integer('driver_phone');
            $table->integer('descr');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet'],'outlet_receivings_sum_index');

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlet_receivings_sum');
    }
}
