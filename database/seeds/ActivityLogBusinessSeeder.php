<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\ActivityLogBusiness;

class ActivityLogBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(ActivityLogBusiness::class,50)->create();
    }
}
