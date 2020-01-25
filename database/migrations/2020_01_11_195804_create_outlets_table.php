<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->string('outlet_id',100)->comment('coded id ');
            $table->string('name',100);
            $table->unsignedBigInteger('biz_id')->comment('the business this outlet belongs to');
            $table->string('address',100);
            $table->string('logo',100);
            $table->string('abbr',100);
            $table->string('phone',100);
            $table->string('email',100);
            $table->unsignedBigInteger('created_by')->comment('the business admin/manager that added this outlet ');
            $table->enum('disabled', [1, 0])->default(0)->comment('This is the admins tool to activate or deactivate this outlet ');
            $table->timestamp('date_disabled');
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
        Schema::dropIfExists('outlets');
    }
}
