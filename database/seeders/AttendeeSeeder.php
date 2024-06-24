<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\Attendee;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use chunking to handle large datasets more efficiently
        User::chunk(200, function ($users) {
            // Retrieve all event IDs as a collection to minimize memory usage
            $eventIds = Event::pluck('id');

            foreach ($users as $user) {
                if ($eventIds->isNotEmpty()) {
                    // Randomly pick 1 to 3 event IDs to attend
                    $eventsToAttendIds = $eventIds->random(rand(1, 3));

                    // Prepare data for bulk insertion
                    $attendeeData = $eventsToAttendIds->map(function ($eventId) use ($user) {
                        return [
                            'user_id' => $user->id,
                            'event_id' => $eventId
                        ];
                    });

                    // Insert data into the database in one query
                    Attendee::insert($attendeeData->toArray());
                }
            }
        });
    }
}
