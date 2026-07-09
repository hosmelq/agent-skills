<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

use JsonException;

final class Renderer
{
    /**
     * @param array<string, mixed> $context
     */
    public static function json(array $context): string
    {
        try {
            return json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
        } catch (JsonException $exception) {
            throw new ContextException("Could not render JSON: {$exception->getMessage()}");
        }
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function markdown(array $context): string
    {
        $lines = ['# Laravel Project Pattern Context', ''];
        $operation = $context['inputs']['operation'] ?? null;
        $operationSource = $context['inputs']['operation_source'] ?? null;

        foreach ($context['matches'] as $match) {
            $lines[] = sprintf('- `%s` -> `%s` (%s)', $match['path'], $match['owner'], $match['rule']);
        }

        if (is_string($operation)) {
            $lines[] = sprintf('- Operation: `%s` (%s)', $operation, $operationSource);
        }

        if ($context['inputs']['concerns'] !== []) {
            $lines[] = '- Concerns: `'.implode('`, `', $context['inputs']['concerns']).'`';
        }

        if ($context['inputs']['selectors'] !== []) {
            $lines[] = '- Progressive selections: `'.implode('`, `', $context['inputs']['selectors']).'`';
        }

        if ($context['inputs']['expansions'] !== []) {
            $lines[] = '- Expanded frontiers: `'.implode('`, `', $context['inputs']['expansions']).'`';
        }

        $lines[] = '';
        $lines[] = '## Read In Order';
        $lines[] = '';

        foreach ($context['references'] as $index => $reference) {
            $lines[] = sprintf(
                '%d. `%s` — %s; %d words; sha256 `%s`',
                $index + 1,
                $reference['path'],
                implode('; ', $reference['reasons']),
                $reference['words'],
                $reference['sha256'],
            );
        }

        $lines[] = '';
        $lines[] = '## Mandatory Gates';
        $lines[] = '';

        foreach ($context['gates'] as $gate) {
            $lines[] = sprintf('- `%s`: `%s#%s`', $gate['id'], $gate['reference'], $gate['anchor']);
        }

        $lines[] = '';
        $lines[] = '## Reference Frontiers';
        $lines[] = '';

        if ($context['frontiers'] === []) {
            $lines[] = '- None.';
        } else {
            foreach ($context['frontiers'] as $frontier) {
                $lines[] = sprintf(
                    '- `%s`: %d choices; rerun this preflight adding `%s`.',
                    $frontier['parent'],
                    $frontier['options_total'],
                    $frontier['expand_argument'],
                );

                foreach ($frontier['options'] as $option) {
                    $lines[] = sprintf('  - `%s`: %s; rerun this preflight adding `%s`.', $option['path'], $option['title'], $option['select_argument']);
                }

                if (is_string($frontier['next_page_arguments'])) {
                    $lines[] = '  - More choices: rerun this preflight with `'.$frontier['next_page_arguments'].'`.';
                }
            }
        }

        $lines[] = '';
        $lines[] = sprintf(
            '_Selected %d references and %d words. Content included: %s._',
            $context['limits']['selected_references'],
            $context['limits']['selected_words'],
            $context['limits']['content_included'] ? 'yes' : 'no',
        );

        if ($context['limits']['content_included']) {
            $lines[] = '';
            $lines[] = '## Selected Content';

            foreach ($context['references'] as $reference) {
                $lines[] = '';
                $lines[] = '### `'.$reference['path'].'`';
                $lines[] = '';
                $lines[] = rtrim($reference['content']);
            }
        }

        return implode(PHP_EOL, $lines).PHP_EOL;
    }
}
