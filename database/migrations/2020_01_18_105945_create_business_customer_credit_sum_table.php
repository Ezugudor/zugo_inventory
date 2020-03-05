<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessCustomerCreditSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_customer_credit_sum', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->bigInteger('bccs_id');
            $table->unsignedBigInteger('customer')->nullable();
            $table->enum('is_outlet', [1, 0])->default(0);
            $table->unsignedBigInteger('outlet')->nullable();
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->integer('deposit');
            $table->integer('balance');
            $table->timestamp('last_payed');
            $table->string('sku_code');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'outlet', 'customer'], 'business_customer_credit_sum_index');

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
        Schema::dropIfExists('business_customer_credit_sum');
    }
}
