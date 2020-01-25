<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletReceivings;

class OutletReceivingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletReceivings::class, 50)->create();
    }
}
