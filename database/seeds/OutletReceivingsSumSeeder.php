<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletReceivingsSum;

class OutletReceivingsSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletReceivingsSum::class, 50)->create();
    }
}
