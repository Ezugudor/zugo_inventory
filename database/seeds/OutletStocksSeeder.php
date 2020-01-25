<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletStocks;

class OutletStocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletStocks::class, 50)->create();
    }
}
