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
    use RefreshDatabase, WithFaker;

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

        $this->actingAs($user)->get(route('prompts.index'))
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
            'body' => 'This is a test {{ prompt }}. ' . $this->faker->sentence(),
            '_token' => csrf_token()
        ];

        $response = $this->actingAs($user)->call('POST', route('prompts.store'), $prompt);

        $response->assertRedirect();
    }


    /**
     * Test for user created prompt.
     */
    public function test_a_user_can_create_a_long_prompt(): void
    {
        $user = User::factory()->create();

        $prompt = [
            'body' => 'This is a test {{ prompt }}. ' . $this->faker->paragraph(100),
            '_token' => csrf_token()
        ];

        $response = $this->actingAs($user)->call('POST', route('prompts.store'), $prompt);

        $response->assertRedirect();
    }

    /**
     * Test API endpoint for fetching prompt search results
     */
    public function test_an_empty_search_query_returns_all_prompts()
    {
        $prompts = Prompt::factory(rand(6, 28))->create();

        $search = ['search' => ''];

        $response = $this->call('GET', route('prompts.search'), $search);

        $this->assertCount($prompts->count(), $response->json());
    }

    /**
     * Test API endpoint for fetching prompt search results
     */
    public function test_an_unmatched_search_query_returns_no_prompts()
    {
        $prompts = Prompt::factory(rand(6, 28))->create();

        $searchFull = ['search' => 'some specific search term that is certainly unmatchable with numbers just to be safe 98767912'];

        $responseFull = $this->call('GET', route('prompts.search'), $searchFull);

        $this->assertNotCount($prompts->count(), $responseFull->json());
        $this->assertCount(0, $responseFull->json());
    }
}
