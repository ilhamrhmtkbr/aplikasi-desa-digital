<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\HeadOfFamily;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::all();
        $headOFFamilies = HeadOfFamily::all();

        foreach ($events as $event) {
            foreach ($headOFFamilies as $headOFFamily) {
                EventParticipant::factory()->create([
                    'event_id' => $event->id,
                    'head_of_family_id' => $headOFFamily->id
                ]);
            }
        }
    }
}
