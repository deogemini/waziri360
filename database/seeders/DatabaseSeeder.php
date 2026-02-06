<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tag;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'role' => 'admin', 'password' => bcrypt('password')]
        );

        Tag::query()->firstOrCreate(['name' => 'Education']);
        Tag::query()->firstOrCreate(['name' => 'Health']);
        Tag::query()->firstOrCreate(['name' => 'Infrastructure']);

        Setting::query()->firstOrCreate(
            ['id' => 1],
            ['audit_enabled' => false, 'retention_days' => 90]
        );
    }
}
