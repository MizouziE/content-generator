<?php

namespace App\Jobs;

use App\Models\ContentTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public static function handle(): array
    {
        $tmp = ContentTemplate::first();
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
                foreach ($substitutes as $substitute => $position) {
                    foreach (json_decode($tmp->prompts) as $prompt) {

                        if (str_contains($prompt, "{{ " . $substitute . " }}")) {

                            $promptRow[] = str_replace("{{ " . $substitute . " }}", $row[$position], $prompt);
                        }
                    }
                }

                $promptList[] = $promptRow;
            }

            // add counter
            $i++;
        } while (!feof($file));
        fclose($file);

        // Construct groups of switched out prompts per row
        return $promptList;

        // Send prompts to OpenAI

        // Concatenate and Save responses, per row

        // Notify user of availabilty of content at given url
    }
}
