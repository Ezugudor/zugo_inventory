<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogSystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_system', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity');
            $table->unsignedBigInteger('admin');
            $table->string('client_ip',50);
            $table->string('browser',200);
            $table->timestamps();

            // indexing
            $table->index(['activity', 'admin']);

            // relations
            $table->foreign('activity')
                ->references('id')
                ->on('activity_type')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('admin')
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
        Schema::dropIfExists('activity_log_system');
    }
}
