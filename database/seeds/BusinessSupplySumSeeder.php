<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessSupplySum;

class BusinessSupplySumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessSupplySum::class, 50)->create();
    }
}
