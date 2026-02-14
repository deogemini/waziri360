<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tag;
use App\Models\Setting;
use App\Models\User as ModelsUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\TagSeeder;

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

        $this->call(TagSeeder::class);

        Setting::query()->firstOrCreate(
            ['id' => 1],
            ['audit_enabled' => false, 'retention_days' => 90]
        );

        ModelsUser::query()->firstOrCreate(
            ['email' => 'deputy@example.com'],
            ['name' => 'Deputy User', 'role' => 'deputy', 'password' => bcrypt('password')]
        );
    }
}
