<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessCreditPayment;

class BusinessCreditPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessCreditPayment::class,50)->create();
    }
}
