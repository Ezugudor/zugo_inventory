<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessStocks;

class BusinessStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessStocks::class, 50)->create();
    }
}
