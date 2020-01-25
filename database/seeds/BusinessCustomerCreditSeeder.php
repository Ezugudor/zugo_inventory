<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessCustomerCredit;

class BusinessCustomerCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessCustomerCredit::class,50)->create();
    }
}
