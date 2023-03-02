<?php

namespace App\Actions;

class Prompts
{
    public static function prepare(array $patterns, array $replacements, array $subjects): array
    {
        foreach ($patterns as $i => $pattern) {
            $patterns[$i] = '/{{\s?' . $pattern . '\s?}}/i';
        }

        return preg_replace($patterns, $replacements, $subjects);
    }
}