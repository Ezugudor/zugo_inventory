<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SystemAdminRole;

class SystemAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * index 0 : the role
         * index 1 : the role_desc
         */
        $roles = array(
            ['Moderator', null],
            ['Super Admin', null],
            ['Helper Admin', null],
            ['Auditor', null],
        );


        for ($i = 0; $i < count($roles); $i++) {
            SystemAdminRole::create([
                'role' => $roles[$i][0],
                'role_desc' => $roles[$i][1],
            ]);
        }
    }
}
