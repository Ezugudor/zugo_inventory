<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\Businesses;

class BusinessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(Businesses::class, 50)->create();
    }
}
