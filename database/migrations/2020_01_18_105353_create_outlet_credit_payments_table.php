<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletCreditPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlet_credit_payments', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('ocp_id');
            $table->unsignedBigInteger('customer');
            $table->integer('amount');
            $table->enum('payment_type',['cash','transfer','cheque'])->default('cash');
            $table->string('payment_desc');
            $table->string('receipt_id');
            $table->unsignedBigInteger('occs_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet','occs_id','customer'],'outlet_credit_payments_index');

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
        Schema::dropIfExists('outlet_credit_payments');
    }
}
