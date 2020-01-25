<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\BusinessAdmin;

class BusinessAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(BusinessAdmin::class,50)->create();
    }
}
