<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessSuppliers;

class BusinessSuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessSuppliers::class,50)->create();
    }
}
