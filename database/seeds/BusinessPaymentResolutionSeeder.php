<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessPaymentResolution;

class BusinessPaymentResolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessPaymentResolution::class, 50)->create();
    }
}
