<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCreditPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_credit_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bcp_id');
            $table->unsignedBigInteger('customer')->nullable();
            $table->enum('is_outlet', [1, 0])->default(0);
            $table->unsignedBigInteger('outlet')->nullable();
            $table->integer('amount');
            $table->enum('payment_type',['cash','transfer','cheque'])->default('cash');
            $table->string('payment_desc');
            $table->string('receipt_id');
            $table->unsignedBigInteger('bccs_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','outlet','bccs_id','customer'],'business_credit_payment_index');

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

             $table->foreign('outlet')
                 ->references('id')
                 ->on('outlets')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('bccs_id')
                 ->references('id')
                 ->on('business_customer_credit_sum')
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
        Schema::dropIfExists('business_credit_payment');
    }
}
