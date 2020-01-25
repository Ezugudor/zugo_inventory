<?php

use Illuminate\Database\Seeder;
use App\Api\V1\Models\SystemSettings;


class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemSettings::create([
            'business_name' => 'Zugo Inventory Management',
            'business_title' => 'The Easy Inventory System',
            'business_desc' => null,
            'business_email' => null,
            'session_expires' => 3600,
            'contact' => null,
            'site_other_name' => 1000000,
            'slogan' => 'Managing Businesses , the easy way.',
            'about' => null,
            'terms' => null,
            'can_login' => 1,
        ]);
    }
}
