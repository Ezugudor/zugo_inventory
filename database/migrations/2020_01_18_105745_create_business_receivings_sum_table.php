<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessReceivingsSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_receivings_sum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('brs_id');
            $table->string('supply_code');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->unsignedBigInteger('supplier');
            $table->integer('driver')->nullable();
            $table->integer('truck_id')->nullable();
            $table->integer('driver_phone')->nullable();
            $table->enum('mode',['DD','MDD'])->default('DD')->comment('DD=Direct Delivery,no need for driver info. MDD=Modified Direct Del');
            $table->integer('size')->nullable();
            $table->enum('source',['depot','factory'])->default('factory');
            $table->integer('invoice')->nullable();
            $table->integer('descr')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','supplier'],'business_receivings_sum_index');

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

             $table->foreign('supplier')
                 ->references('id')
                 ->on('business_suppliers')
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
        Schema::dropIfExists('business_receivings_sum');
    }
}
