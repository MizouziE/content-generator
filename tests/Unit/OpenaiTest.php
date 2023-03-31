<?php

namespace Tests\Unit;

use OpenAI\Laravel\Facades\OpenAI;
use Tests\TestCase;

class OpenaiTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_app_can_connect_to_openai(): void
    {
        $complete = OpenAI::completions()->create([
            'model' => 'text-davinci-003',
            'prompt' => 'Hello',
            'max_tokens' => 10,
        ]);

        $this->assertArrayHasKey('model', $complete);
        $this->assertArrayHasKey('choices', $complete);
        $this->assertArrayHasKey(0, $complete->choices);
        $this->assertArrayHasKey('text', $complete->choices[0]->toArray());
    }
}
