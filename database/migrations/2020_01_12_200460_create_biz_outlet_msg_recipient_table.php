<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizOutletMsgRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biz_outlet_msg_recipient', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('msg_id');
            $table->unsignedBigInteger('recipient')->comment('With value, for_group should be null.')->nullable();
            $table->unsignedBigInteger('for_group')->nullable()->comment('the group of persons that has this message. With value, recipient should be null. ');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
            $table->enum('seen',[0,1])->default(0);
            $table->timestamp('date_seen');
            $table->timestamps();

            // indexing
            $table->index(['msg_id', 'recipient','biz_id','outlet']);

            // relations
            $table->foreign('msg_id')
                ->references('id')
                ->on('biz_outlet_msg')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('recipient')
                ->references('id')
                ->on('outlet_admin')
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
        Schema::dropIfExists('biz_outlet_msg_recipient');
    }
}
