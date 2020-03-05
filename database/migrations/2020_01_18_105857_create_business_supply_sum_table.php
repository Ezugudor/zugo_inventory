<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSupplySumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_supply_sum', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('bss_id');
            $table->string('sku_code');
            $table->integer('total_items');
            $table->integer('total_price')->comment("the total price / worth of the total items to be supplied");
            $table->integer('amount_paid')->comment("the amount paid(if payment method is FULL)/deposited(if PART) by the customer for this transaction");
            $table->string('invoice', 100);
            $table->enum('payment_method', ['part', 'full', 'none'])->default('full');
            $table->enum('is_outlet', [1, 0])->default(0);
            $table->unsignedBigInteger('outlet')->nullable()->comment('should have value if is_outlet = 1');
            $table->unsignedBigInteger('customer')->nullable()->comment('will be null if is_outlet = 1');
            $table->enum('mode', ['mdd', 'dd'])->default('mdd');
            $table->enum('source', ['factory', 'depot'])->default('factory');
            $table->unsignedBigInteger('driver')->nullable()->comment('the driver that made this supply');
            $table->string('comment', 500)->comment("additional comment for this supply");
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'customer', 'outlet', 'driver'], 'business_supply_sum_index');

            // relations
            $table->foreign('created_by')
                ->references('id')
                ->on('business_admin')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('customer')
                ->references('id')
                ->on('customer_business')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('outlet')
                ->references('id')
                ->on('outlets')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('biz_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('driver')
                ->references('id')
                ->on('business_driver')
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
        Schema::dropIfExists('business_supply_sum');
    }
}
