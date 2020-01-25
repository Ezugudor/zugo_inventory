<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BizOutletMsgSeen;

class BizOutletMsgSeenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BizOutletMsgSeen::class,50)->create();
    }
}
