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
            $table->bigIncrements('id');
            $table->integer('bss_id');
            $table->integer('total_items');
            $table->integer('total_amount');
            $table->string('invoice', 100);
            $table->enum('payment_method', ['part', 'full', 'none'])->default('full');
            $table->enum('is_outlet',[1,0])->default(0);
            $table->unsignedBigInteger('outlet')->nullable()->comment('should have value if is_outlet = 1');
            $table->unsignedBigInteger('customer')->nullable()->comment('will be null if is_outlet = 1');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('biz_id');
            $table->timestamps();

            // indexing
            $table->index(['created_by', 'biz_id', 'customer', 'outlet'],'business_supply_sum_index');

            // relations
            $table->foreign('created_by')
                ->references('id')
                ->on('outlet_admin')
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
