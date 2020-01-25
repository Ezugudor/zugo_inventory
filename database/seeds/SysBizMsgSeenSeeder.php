<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SysBizMsgSeen;

class SysBizMsgSeenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(SysBizMsgSeen::class, 50)->create();
    }
}
