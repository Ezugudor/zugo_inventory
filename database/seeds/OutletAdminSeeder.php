<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\OutletAdmin;

class OutletAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = factory(OutletAdmin::class, 50)->create();
    }
}
