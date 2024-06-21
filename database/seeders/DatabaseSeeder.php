<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(33)->create()->each(function ($book) {
            $numViews = random_int(5, 30);

            Review::factory()->count($numViews)
                ->good()
                ->for($book)
                ->create();
        });

        Book::factory(33)->create()->each(function ($book) {
            $numViews = random_int(5, 30);

            Review::factory()->count($numViews)
                ->average()
                ->for($book)
                ->create();
        });

        Book::factory(3)->create()->each(function ($book) {
            $numViews = random_int(5, 30);

            Review::factory()->count($numViews)
                ->bad()
                ->for($book)
                ->create();
        });
    }
}
