<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\CustomerOutlet;

class CustomerOutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(CustomerOutlet::class, 50)->create();
    }
}
