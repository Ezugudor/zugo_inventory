<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BizOutletMsgRecipient;

class BizOutletMsgRecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BizOutletMsgRecipient::class,50)->create();
    }
}
