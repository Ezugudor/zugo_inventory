<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletSales;

class OutletSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletSales::class, 50)->create();
    }
}
