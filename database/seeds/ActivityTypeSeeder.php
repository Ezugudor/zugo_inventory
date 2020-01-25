<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\ActivityType;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        /**
         * index 0 : the activity_name
         * index 1 : the activity_desc
         */
        $roles = array(
            ['stock price change', 'stock price change'],
            ['New stock item added', 'New stock item added'],
            ['Remove stock item', null],
            ['Added Outlet', null],
            ['Removed Outlet', null],
            ['Updated Outlet', 'A business admin has updated an outlet info'],
            ['Added Busines', null],
            ['Removed Busines', null],
            ['Removed Busines', null],
            ['Updated Busines', 'A system admin has updated a business info'],
            ['Business subscribed', null],
        );


        for ($i = 0; $i < count($roles); $i++) {
            ActivityType::create([
                'activity_name' => $roles[$i][0],
                'activity_desc' => $roles[$i][1],
            ]);
        }
    }
}
