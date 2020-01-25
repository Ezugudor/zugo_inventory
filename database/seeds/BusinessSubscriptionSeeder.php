<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessSubscription;

class BusinessSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessSubscription::class, 50)->create();
    }
}
