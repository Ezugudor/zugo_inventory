<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SystemAdmin;

class SystemAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(SystemAdmin::class, 50)->create();
    }
}
