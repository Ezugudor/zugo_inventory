<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\ActivityLogSystem;

class ActivityLogSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(ActivityLogSystem::class,50)->create();
    }
}
