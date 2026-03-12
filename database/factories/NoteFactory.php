<?php

namespace Database\Factories;

use App\Models\Note;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        $category = fake()->randomElement(['Work', 'Personal', 'Study']);

        return [
            'title' => fake()->sentence(3),
            'content' => fake()->paragraphs(2, true),
            'category' => $category,
        ];
    }
}
