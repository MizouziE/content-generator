<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Jobs\SendPromptRow;
use App\Actions\Prompts;

class Spreadsheet implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public function __construct(
        public array $substitutes,
        public array $prompts,
        public int $contentTemplateId,
        public int $maxTokens = 50
    ) {
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {

            $replacements = [];

            foreach ($this->substitutes as $substitute) {
                $replacements[] = $row[$substitute];
            }

            $prompts = Prompts::prepare($this->substitutes, $replacements, $this->prompts);            
            
            SendPromptRow::dispatch($prompts, $this->contentTemplateId, $this->maxTokens);

        }
    }

    public function chunkSize(): int
    {
        return 10;
    }
}
