<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_business', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->string('customer_id',100)->comment('coded id ');
            $table->string('surname',100)->nullable();
            $table->string('firstname',100)->nullable();
            $table->unsignedBigInteger('biz_id')->comment('the business this customer belongs to');
            $table->string('address',100)->nullable();
            $table->string('avatar',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->unsignedBigInteger('created_by')->comment('the business admin/manager that added this customer ');
            $table->timestamps();

            // indexing
            $table->index(['biz_id','created_by']);

            // relations
            $table->foreign('biz_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('business_admin')
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
        Schema::dropIfExists('customer_business');
    }
}
