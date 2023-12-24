<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $title = fake()->unique()->jobTitle();
            $slug = Str::slug($title);

            $space = Space::create([
                'title' => $title,
                'slug' => $slug,
                'description' => fake()->text()
            ]);

            $space->attachUsers(User::inRandomOrder()
                                    ->limit(5)
                                    ->get()->pluck('id'));
        }
    }
}
