<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Setting::set('today_link', null);
        Setting::set('tomorrow_link', null);
        Setting::set('support_phone', '+971 4 301 7777');
        Setting::set('payment_qr', null);
    }
}
