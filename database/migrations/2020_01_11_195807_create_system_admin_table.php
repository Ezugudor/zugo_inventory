<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_admin', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->bigIncrements('id');
            $table->string('username', 100);
            $table->string('password', 100);
            $table->unsignedBigInteger('role')->comment('The role of this admin user. Referencing the role table.');
            $table->string('surname',20);
            $table->string('firstname',20);
            $table->string('phone',20);
            $table->string('email',50)->unique();
            $table->string('avatar',50)->nullable();
            $table->string('user_code', 30)->nullable();
            $table->string('email_veri_code', 100)->nullable();
            $table->enum('email_verified', [1, 0])->default(0);
            $table->string('phone_veri_code', 20)->nullable();
            $table->enum('phone_verified', [1, 0])->default(0);
            $table->timestamp('last_login')->comment('for new account this time is the CURRENT TIMESTAMP but for subsequent the time is UPDATED ');
            $table->timestamp('last_action')->comment('last action user performed in the system this is used to know when to timeout the session ');
            $table->enum('user_disabled', [1, 0])->default(0)->comment('This is the admins tool to activate or deactivate this user ');
            $table->timestamp('date_disabled');
            $table->enum('user_banned', [1, 0])->default(0)->comment('This user has commited an offence and now banned from the casino house. Likely to be blacklisted for the general public to see and be aware. ');
            $table->timestamp('date_banned');
            $table->string('activation_code', 200)->nullable();
            $table->enum('activation_code_activated', [1, 0])->default(0);
            $table->timestamp('activation_code_expire');
            $table->timestamps();
            $table->softDeletes();

             // indexing
             $table->index(['role']);

             // relations
             $table->foreign('role')
                 ->references('id')
                 ->on('system_admin_role')
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
        Schema::dropIfExists('system_admin');
    }
}
