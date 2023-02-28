<?php

namespace App\Jobs;

use App\Models\Content;
use App\Models\ContentTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Orhanerday\OpenAi\OpenAi;

class ProcessContentTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContentTemplate $contentTemplate,
    ) {
    }

    /**
     * Execute the job.
     */
    public static function handle(int $id): void
    {
        $tmp = ContentTemplate::find($id);
        $substitutes = [];

        // Open file
        $file = fopen("storage/app/" . $tmp->csv_path, 'r');

        // Set iterator count
        $i = 0;

        // Start iteration
        do {
            $row = fgetcsv($file, 0);

            // exit if EOF
            if ($row === false) {
                break;
            }


            // Heading row
            if ($i < 1) {
                // TODO: Check that columns match given csv file

                // Make substitute map
                foreach ($row as $index => $heading) {
                    if (in_array($heading, json_decode($tmp->columns))) {

                        $substitutes += [$heading => $index];
                    }
                }
            } else {
                // Wipe prompt array
                $promptRow = [];

                // Swap out substitutes in each prompt per row
                foreach (json_decode($tmp->prompts) as $prompt) {
                    foreach ($substitutes as $substitute => $position) {

                        if (str_contains($prompt, "{{ " . $substitute . " }}")) {

                            $prompt = str_replace("{{ " . $substitute . " }}", $row[$position], $prompt);
                        }
                    }
                    $promptRow[] = $prompt;
                }

                // Construct groups of switched out prompts per row
                $promptList[] = $promptRow;
            }

            // add counter
            $i++;
        } while (!feof($file));
        fclose($file);

        // Send prompts to OpenAI
        $openAI = new OpenAi(env('OPENAI_API_KEY'));

        foreach ($promptList as $articleTemplate) {

            $articleOutput = '';

            foreach ($articleTemplate as $singlePrompt) {

                $complete = $openAI->completion([
                    'model' => 'text-davinci-003',
                    'prompt' => $singlePrompt,
                    'max_tokens' => 50,
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


        // TODO: Notify user of availabilty of content at given url
    }
}
