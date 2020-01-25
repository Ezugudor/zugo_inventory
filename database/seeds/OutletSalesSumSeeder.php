<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletSalesSum;

class OutletSalesSumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletSalesSum::class, 50)->create();
    }
}
