<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Kazi',
            'Ajira',
            'Mahusiano',
            'Kinga ya Jamii',
        ];

        foreach ($names as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }
    }
}
