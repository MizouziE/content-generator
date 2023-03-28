<?php

namespace Tests\Feature\Models;

use App\Models\Prompt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class PromptTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test.
     */
    public function test_a_prompt_can_be_created(): void
    {
        $prompt = Prompt::factory()->create();

        $dbPrompt = Prompt::first();

        $this->assertEquals($prompt['body'], $dbPrompt->body);
        $this->assertEquals($prompt['content_template_id'], $dbPrompt->content_template_id);
    }

    /**
     * Test for user seeing prompt list.
     */
    public function test_a_user_can_see_a_prompt(): void
    {
        $user = User::factory()->create();

        $prompt = Prompt::factory()->create();

        $this->actingAs($user)->get('/prompts/' . $prompt->id)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Prompts/Show')
                    ->has(
                        'prompt',
                        fn (Assert $page) => $page
                            ->where('id', $prompt->id)
                            ->where('body', $prompt->body)
                            ->etc()
                    )
            );
    }

    /**
     * Test for user seeing prompt list.
     */
    public function test_a_user_can_see_a_list_of_prompts(): void
    {
        $user = User::factory()->create();

        $prompts = Prompt::factory(5)->create();

        $this->actingAs($user)->get('/prompts/')
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Prompts/Index')
                    ->has(
                        'prompts', 5,
                        // fn (Assert $page) => $page
                        //     ->where('id', $prompts->id)
                        //     ->where('body', $prompts->body)
                        //     ->etc()
                    )
            );
    }

    /**
     * Test for user created prompt.
     */
    public function test_a_user_can_create_a_prompt(): void
    {
        $user = User::factory()->create();

        $prompt = [
            'body' => 'This is a test {{ prompt }}',
            'content_template_id' => null,
            '_token' => csrf_token()
        ];

        $response = $this->actingAs($user)->call('POST', '/prompts', $prompt);

        $response->assertOk();
    }
}
