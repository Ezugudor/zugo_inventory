<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\CustomerBusiness;

class CustomerBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(CustomerBusiness::class, 50)->create();
    }
}
