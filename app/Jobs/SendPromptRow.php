<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\ContentTemplate;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Orhanerday\OpenAi\OpenAi;

class SendPromptRow implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $articleTemplate,
        private OpenAi $openAi,
        private ContentTemplate $tmp
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()->cancelled()) {
            // Determine if the batch has been cancelled...
 
            return;
        }
        
        $articleTemplate = $this->articleTemplate;
        $openAI = $this->openAi;
        $tmp = $this->tmp;
        
        $articleOutput = '';

        foreach ($articleTemplate as $singlePrompt) {

            $complete = $openAI->completion([
                'model' => 'text-davinci-003',
                'prompt' => $singlePrompt,
                'max_tokens' => $tmp->max_tokens ?? 50,
            ]);
            
            // Concatenate and Save responses, per row
            $articleOutput .= json_decode($complete)->choices[0]->text;
        }

        $article = Content::create([
            'body' => $articleOutput,
        ]);

        $article->contentTemplate()->associate($tmp);
        $article->save();
    }
}
