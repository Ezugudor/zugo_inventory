<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SysBizMsg;

class SysBizMsgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(SysBizMsg::class, 50)->create();
    }
}
