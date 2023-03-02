<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Actions\Prompts;

class PromptsTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_prepare_prompts(): void
    {
        $patterns = [
            'test',
            'anotherTest',
            'lastTest'
        ];

        $replacements = [
            'replacement one',
            'replacement two',
            'replacement three'
        ];

        $subjects = [
            'This is a {{ test }}',
            'Here is {{anotherTest}}, but not the {{ lastTest }}',
            'This may be the {{ lastTest }}, but not the first {{ test}}',
            'This may be the {{lastTest }}, but not the first {{test }}'
        ];

        $goal = [
            'This is a replacement one',
            'Here is replacement two, but not the replacement three',
            'This may be the replacement three, but not the first replacement one',
            'This may be the replacement three, but not the first replacement one'
        ];

        $results = Prompts::prepare($patterns, $replacements, $subjects);

        $this->assertEquals(json_encode($goal), json_encode($results));
    }
}
