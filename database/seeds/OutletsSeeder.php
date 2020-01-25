<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\Outlets;

class OutletsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(Outlets::class, 50)->create();
    }
}
