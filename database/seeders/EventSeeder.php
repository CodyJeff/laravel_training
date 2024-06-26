<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch only the IDs to reduce memory usage
        $userIds = User::pluck('id');

        if ($userIds->isEmpty()) {
            return;
        }

        for ($i=0; $i < 200 ; $i++) { 
            $userId = $userIds->random();

            Event::factory()->create([
                'user_id' => $userId
            ]);
        }
    }
}
