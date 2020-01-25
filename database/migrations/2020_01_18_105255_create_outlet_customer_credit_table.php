<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletCustomerCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_customer_credit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('occ_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->integer('total_amount');
            $table->unsignedBigInteger('occs_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet','occs_id','product_id'],'outlet_customer_credit_index');

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

             $table->foreign('occs_id')
                 ->references('id')
                 ->on('outlet_customer_credit_sum')
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
        Schema::dropIfExists('outlet_customer_credit');
    }
}
