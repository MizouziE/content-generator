<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentTemplates>
 */
class ContentTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'columns' => json_encode([
                'adjective',
                'noun',
                'verb'
            ]),
            'prompts' => json_encode([
                'Write a tagline about a {{ adjective }} {{ noun }} that is {{ verb }}',
                'Write a haiku about a {{ noun }} that is {{ verb }}',
                'List  alternative words for {{ adjective }}'
            ]),
            'max_tokens' => 500,
            'csv_path' => 'test.csv'
        ];
    }
}
