<?php

use App\Api\V1\Models\BusinessReceivingsSum;
use Illuminate\Database\Seeder;

class BusinessReceivingsSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessReceivingsSum::class, 50)->create();
    }
}
