<?php

namespace Tests\Feature\Models;

use App\Models\ContentTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ContentTemplateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test.
     */
    public function test_a_content_template_can_be_created(): void
    {
        $contentTemplate = ContentTemplate::factory()->create();

        $dbContentTemplate = ContentTemplate::first();

        $this->assertEquals($contentTemplate['columns'], $dbContentTemplate->columns);
        $this->assertEquals($contentTemplate['prompts'], $dbContentTemplate->prompts);
        $this->assertEquals($contentTemplate['id'], $dbContentTemplate->id);
    }

    /**
     * Test for user seeing prompt list.
     */
    public function test_a_user_can_see_a_template(): void
    {
        $user = User::factory()->create();

        $template = ContentTemplate::factory()->create();

        $this->actingAs($user)->get(route('contentTemplates.show', $template->id))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('ContentTemplates/Show')
                    ->has(
                        'template',
                        fn (Assert $page) => $page
                            ->where('id', $template->id)
                            ->where('prompts', $template->prompts)
                            ->where('content', $template->content)
                            ->etc()
                    )
            );
    }

    /**
     * Test for user seeing template list.
     */
    public function test_a_user_can_see_a_list_of_templates(): void
    {
        $user = User::factory()->create();

        $templates = ContentTemplate::factory(5)->create();

        $this->actingAs($user)->get(route('contentTemplates.index'))
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('ContentTemplates/Index')
                    ->has(
                        'templates',
                        5
                    )
                    ->has(
                        'templates.0',
                        fn (Assert $template) => $template
                            ->where('columns', $templates->first()->columns)
                            ->where('csv_path', $templates->first()->csv_path)
                            ->has('content')
                            ->etc()
                    )
                    ->etc()

            );
    }

    /**
     * Test for user created prompt.
     */
    public function test_a_user_cannot_create_a_template_without_a_spreadsheet(): void
    {
        $user = User::factory()->create();

        $prompt = [
            'max_tokens' => 500,
            'columns' => [
                "adjective",
                "noun",
                "verb"
            ],
            'prompts' => [
                "Write a tagline about a {{ adjective }} {{ noun }} that is {{ verb }}",
                "Write a haiku about a {{ noun }} that is {{ verb }}",
                "List  alternative words for {{ adjective }}"
            ],
            '_token' => csrf_token()
        ];

        $response = $this->actingAs($user)
            ->call('POST', route('contentTemplates.store'), $prompt);

        $response->assertFound();
        $response->assertInvalid();
    }
}
