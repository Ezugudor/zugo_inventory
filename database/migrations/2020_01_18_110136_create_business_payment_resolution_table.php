<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPaymentResolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_payment_resolution', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('bpr_id');
            $table->unsignedBigInteger('payment_id');
            $table->integer('payment_amount');
            $table->unsignedBigInteger('bccs_id');
            $table->integer('bccs_amount_before');
            $table->integer('bccs_amount_after');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id','bccs_id','payment_id'],'business_payment_resolution_index');

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

            $table->foreign('payment_id')
                ->references('id')
                ->on('business_credit_payment')
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
        Schema::dropIfExists('business_payment_resolution');
    }
}
