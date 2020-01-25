<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessReceivings;

class BusinessReceivingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessReceivings::class, 50)->create();
    }
}
