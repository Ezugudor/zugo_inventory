<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessCustomerCreditSum;

class BusinessCustomerCreditSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessCustomerCreditSum::class, 50)->create();
    }
}
