<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCustomerCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_customer_credit', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('bcc_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty');
            $table->integer('total_amount');
            $table->unsignedBigInteger('bccs_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

             // indexing
             $table->index(['created_by', 'biz_id','bccs_id','product_id'],'business_customer_credit_index');

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

             $table->foreign('bccs_id')
                 ->references('id')
                 ->on('business_customer_credit_sum')
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
        Schema::dropIfExists('business_customer_credit');
    }
}
