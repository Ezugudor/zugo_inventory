<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletCustomerCreditSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_customer_credit_sum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('occs_id');
            $table->unsignedBigInteger('customer');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->integer('deposit');
            $table->integer('balance');
            $table->timestamp('last_payed');
            $table->unsignedBigInteger('oss_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet','oss_id','customer'],'outlet_customer_credit_sum_index');

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

             $table->foreign('oss_id')
                 ->references('id')
                 ->on('outlet_sales_sum')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('customer')
                 ->references('id')
                 ->on('customer_business')
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
        Schema::dropIfExists('outlet_customer_credit_sum');
    }
}
