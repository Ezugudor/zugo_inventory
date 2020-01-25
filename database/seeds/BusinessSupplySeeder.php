<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessSupply;

class BusinessSupplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessSupply::class, 50)->create();
    }
}
