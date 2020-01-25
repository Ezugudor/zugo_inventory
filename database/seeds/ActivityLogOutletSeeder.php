<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\ActivityLogOutlet;

class ActivityLogOutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(ActivityLogOutlet::class,50)->create();
    }
}
