<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\ContentTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenAI\Laravel\Facades\OpenAI;

class SendPromptRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $prompts,
        public int $contentTemplateId,
        public int $maxTokens = 50
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $prompts = $this->prompts;
        $tmp = ContentTemplate::find($this->contentTemplateId);
        
        $articleOutput = '';
        
        // Send prompts to OpenAI
        // $openAI = new OpenAi(env('OPENAI_API_KEY'));

        foreach ($prompts as $prompt) {

            $complete = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => $this->maxTokens,
            ]);
            
            // Concatenate and Save responses, per row
            $articleOutput .= $complete['choices'][0]['text'];
        }

        $article = Content::create([
            'body' => $articleOutput,
            'content_template_id' => $this->contentTemplateId
        ]);
    }
}
