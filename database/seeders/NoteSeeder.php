<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $notes = [
            [
                'title' => 'Sprint Planning',
                'content' => 'Finalize tickets and estimate scope for the next release.',
                'category' => 'Work',
            ],
            [
                'title' => 'Grocery List',
                'content' => 'Milk, eggs, coffee beans, and whole grain bread.',
                'category' => 'Personal',
            ],
            [
                'title' => 'Project Ideas',
                'content' => 'Draft concept for offline-first notes with sync conflict handling.',
                'category' => 'Study',
            ],
            [
                'title' => 'Reading Notes',
                'content' => 'Summarize chapter on distributed consistency models.',
                'category' => 'Study',
            ],
            [
                'title' => 'Travel Checklist',
                'content' => 'Passport, power adapter, hotel confirmation, and local SIM options.',
                'category' => 'Personal',
            ],
            [
                'title' => 'Meeting Recap',
                'content' => 'Capture key decisions and assign owners for follow-up actions.',
                'category' => 'Work',
            ],
        ];

        foreach ($notes as $note) {
            Note::query()->create($note);
        }
    }
}
