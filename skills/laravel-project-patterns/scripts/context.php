<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Catalog;
use LaravelProjectPatterns\Context\ContextException;
use LaravelProjectPatterns\Context\MarkdownGraph;
use LaravelProjectPatterns\Context\Renderer;
use LaravelProjectPatterns\Context\Resolver;

require_once __DIR__.'/bootstrap.php';

const USAGE = <<<'TEXT'
Usage:
  php scripts/context.php --path=<project-path> [--path=<second-path>]
      [--operation=<name>] [--concern=<name>] [--concern=<second-name>]
      [--select=<immediate-next-reference>] [--select=<next-reference>]
      [--expand=<loaded-reference>] [--offset=<count>] [--max-options=<count>]
      [--format=markdown|json] [--include-content]
      [--max-references=<count>] [--max-words=<count>]
  php scripts/context.php --list [--format=markdown|json]

The catalog is path-first. Operations and concerns only narrow or extend the
matched owner surfaces. Selected Markdown remains the source of truth.
TEXT;

$options = getopt('', [
    'path:',
    'operation:',
    'concern:',
    'select:',
    'expand:',
    'offset:',
    'max-options:',
    'format:',
    'include-content',
    'max-references:',
    'max-words:',
    'list',
    'help',
]);

if ($options === false) {
    fwrite(STDERR, USAGE.PHP_EOL);
    exit(2);
}

if (array_key_exists('help', $options)) {
    fwrite(STDOUT, USAGE.PHP_EOL);
    exit(0);
}

$skillRoot = dirname(__DIR__);

try {
    $catalog = Catalog::load($skillRoot);
    $format = is_string($options['format'] ?? null) ? $options['format'] : 'markdown';

    if (! in_array($format, ['markdown', 'json'], true)) {
        throw new ContextException('Format must be markdown or json.', 2);
    }

    if (array_key_exists('list', $options)) {
        $data = $catalog->data();
        $operations = [];

        foreach ($catalog->operationSets() as $set) {
            $operations = [...$operations, ...array_keys($set)];
        }

        $operations = array_values(array_unique($operations));
        sort($operations, SORT_STRING);
        $listing = [
            'path_rules' => array_map(
                static fn (array $rule): array => [
                    'id' => $rule['id'],
                    'owner' => $rule['owner'],
                    'patterns' => $rule['patterns'],
                ],
                $catalog->pathRules(),
            ),
            'operations' => $operations,
            'concerns' => array_keys($catalog->concerns()),
            'aliases' => [
                'operations' => $data['operation_aliases'] ?? [],
                'concerns' => $data['concern_aliases'] ?? [],
            ],
        ];

        if ($format === 'json') {
            fwrite(STDOUT, Renderer::json($listing));
        } else {
            fwrite(STDOUT, "# Available Laravel Project Pattern Selectors\n\n");
            fwrite(STDOUT, '- Operations: `'.implode('`, `', $operations)."`\n");
            fwrite(STDOUT, '- Concerns: `'.implode('`, `', array_keys($catalog->concerns()))."`\n\n");
            fwrite(STDOUT, "## Path Rules\n\n");

            foreach ($listing['path_rules'] as $rule) {
                fwrite(STDOUT, sprintf("- `%s` -> `%s`\n", $rule['id'], $rule['owner']));
            }
        }

        exit(0);
    }

    $paths = $options['path'] ?? [];
    $paths = is_array($paths) ? array_values($paths) : [$paths];
    $concerns = $options['concern'] ?? [];
    $concerns = is_array($concerns) ? array_values($concerns) : [$concerns];
    $operation = is_string($options['operation'] ?? null) ? $options['operation'] : null;
    $selectors = $options['select'] ?? [];
    $selectors = is_array($selectors) ? array_values($selectors) : [$selectors];
    $expansions = $options['expand'] ?? [];
    $expansions = is_array($expansions) ? array_values($expansions) : [$expansions];
    $maxReferences = isset($options['max-references']) ? filter_var($options['max-references'], FILTER_VALIDATE_INT) : null;
    $maxWords = isset($options['max-words']) ? filter_var($options['max-words'], FILTER_VALIDATE_INT) : null;
    $offset = isset($options['offset']) ? filter_var($options['offset'], FILTER_VALIDATE_INT) : 0;
    $maxOptions = isset($options['max-options']) ? filter_var($options['max-options'], FILTER_VALIDATE_INT) : null;

    if ($maxReferences === false || $maxWords === false || $offset === false || $maxOptions === false) {
        throw new ContextException('Reference, word, offset, and frontier option limits must be integers.', 2);
    }

    $context = (new Resolver($catalog, new MarkdownGraph($skillRoot)))->resolve(
        paths: $paths,
        operation: $operation,
        concerns: $concerns,
        selectors: $selectors,
        expansions: $expansions,
        includeContent: array_key_exists('include-content', $options),
        maxReferences: $maxReferences,
        maxWords: $maxWords,
        offset: $offset,
        maxOptions: $maxOptions,
    );

    fwrite(STDOUT, $format === 'json' ? Renderer::json($context) : Renderer::markdown($context));
} catch (ContextException $exception) {
    fwrite(STDERR, 'context: '.$exception->getMessage().PHP_EOL);
    exit($exception->exitCode);
}
