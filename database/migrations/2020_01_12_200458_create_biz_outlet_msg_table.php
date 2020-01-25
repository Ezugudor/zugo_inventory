<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBizOutletMsgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biz_outlet_msg', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->bigIncrements('id');
            $table->string('title', 200);
            $table->text('body');
            $table->unsignedBigInteger('biz_id');
            $table->enum('for_all', [1, 0])->default(1)->comment('states if this message is meant for all recipients or for some persons.');
            $table->enum('display_type', [1, 0])->default(1)->comment('where 1 means it should popup on user logins ');
            $table->enum('visibility', [1, 0])->default(1);
            $table->timestamps();

             // indexing
             $table->index(['biz_id']);

             // relations
            
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
        Schema::dropIfExists('biz_outlet_msg');
    }
}
