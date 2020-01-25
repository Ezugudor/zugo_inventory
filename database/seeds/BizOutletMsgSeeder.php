<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BizOutletMsg;

class BizOutletMsgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BizOutletMsg::class,50)->create();
    }
}
