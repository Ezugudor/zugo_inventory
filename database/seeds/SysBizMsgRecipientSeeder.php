<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SysBizMsgRecipient;

class SysBizMsgRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(SysBizMsgRecipient::class, 50)->create();
    }
}
