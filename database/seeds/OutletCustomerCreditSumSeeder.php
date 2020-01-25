<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletCustomerCreditSum;

class OutletCustomerCreditSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletCustomerCreditSum::class, 50)->create();
    }
}
