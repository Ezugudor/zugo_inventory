<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_subscription', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->string('subscription_code',100)->unique();
            $table->timestamp('expiry_date');
            $table->enum('expired', [1, 0])->default(0)->comment('cron updates this. 1 means sub has expired. ');
            $table->unsignedBigInteger('biz_id')->comment('the business that made this subscription ');
            $table->unsignedBigInteger('created_by')->comment('the system admin/manager that created this subscription ');
            $table->timestamps();

             // indexing
             $table->index(['created_by','biz_id']);

             // relations
             $table->foreign('biz_id')
                 ->references('id')
                 ->on('businesses')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');

             $table->foreign('created_by')
                 ->references('id')
                 ->on('system_admin')
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
        Schema::dropIfExists('business_subscription');
    }
}
