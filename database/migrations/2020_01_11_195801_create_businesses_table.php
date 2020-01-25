<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->string('biz_id',100);
            $table->string('name',100);
            $table->string('address',100);
            $table->string('slogan',100);
            $table->string('logo',100);
            $table->string('abbr',100);
            $table->string('phone',100);
            $table->string('email',100);
            $table->enum('is_subscribed', [1, 0])->default(0)->comment("Tells if this business has an active subscription. A cron changes this ");
            $table->string('active_sub_code');
            $table->timestamp('last_sub_date');
            $table->timestamp('sub_expiry_date');
            $table->unsignedBigInteger('created_by')->comment('the system admin/manager that added this business ');
            $table->enum('disabled', [1, 0])->default(0)->comment('This is the admins tool to activate or deactivate this business ');
            $table->timestamp('date_disabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
