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
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->integer('brs_id');
            $table->unsignedBigInteger('product')->comment("added this column for business end as the [receivings] table is not needed again");
            $table->string('supply_code');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->unsignedBigInteger('supplier');
            $table->unsignedBigInteger('driver')->nullable();
            $table->enum('is_outlet', ['1', '0'])->default('0')->nullable();
            $table->unsignedBigInteger('outlet')->nullable()->comment("the outlet this code/receiving is supplied to. Since admin is no longer using the SUPPLY section");
            $table->unsignedBigInteger('customer')->nullable()->comment("the customer this code/receiving is supplied to. Since admin is no longer using the SUPPLY section");
            $table->string('truck_id')->nullable();
            $table->string('driver_phone')->nullable();
            $table->enum('mode', ['DD', 'MDD'])->nullable()->default(null)->comment('DD=Direct Delivery,no need for driver info. MDD=Modified Direct Del');
            $table->integer('size')->nullable();
            $table->integer('qty')->comment("added this column for business end as the [receivings] table is not needed again");
            $table->enum('used', ['1', '0'])->default('0')->comment("says if this code has been USED or not");
            $table->timestamp('date_used')->nullable()->comment("added this column for business end as the [receivings] table is not needed again");
            $table->enum('source', ['depot', 'factory'])->default('factory');
            $table->integer('invoice')->nullable();
            $table->integer('descr')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'supplier', 'product', 'driver', 'outlet', 'customer'], 'business_receivings_sum_index');

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

            $table->foreign('product')
                ->references('id')
                ->on('business_stocks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('driver')
                ->references('id')
                ->on('business_driver')
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
