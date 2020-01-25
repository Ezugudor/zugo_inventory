<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletCustomerCredit;

class OutletCustomerCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletCustomerCredit::class, 50)->create();
    }
}
