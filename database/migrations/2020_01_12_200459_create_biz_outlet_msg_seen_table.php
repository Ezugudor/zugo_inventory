<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizOutletMsgSeenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biz_outlet_msg_seen', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('msg_id');
            $table->unsignedBigInteger('recipient');
            $table->unsignedBigInteger('outlet');
            $table->unsignedBigInteger('biz_id');
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
        Schema::dropIfExists('biz_outlet_msg_seen');
    }
}
