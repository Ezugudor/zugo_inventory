<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletCreditPayments;

class OutletCreditPaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletCreditPayments::class, 50)->create();
    }
}
