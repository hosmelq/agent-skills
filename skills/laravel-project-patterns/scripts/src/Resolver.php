<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

final class Resolver
{
    private readonly Frontier $frontier;

    private readonly PathRouter $router;

    public function __construct(
        private readonly Catalog $catalog,
        private readonly MarkdownGraph $graph,
    ) {
        $this->frontier = new Frontier($catalog, $graph);
        $this->router = new PathRouter($catalog);
    }

    /**
     * @param list<string> $paths
     * @param list<string> $concerns
     * @param list<string> $selectors
     * @param list<string> $expansions
     * @return array<string, mixed>
     */
    public function resolve(
        array $paths,
        ?string $operation = null,
        array $concerns = [],
        array $selectors = [],
        array $expansions = [],
        bool $includeContent = false,
        ?int $maxReferences = null,
        ?int $maxWords = null,
        int $offset = 0,
        ?int $maxOptions = null,
    ): array {
        if ($paths === []) {
            throw new ContextException('At least one --path is required.', 2);
        }

        $maxReferences ??= $this->catalog->defaultInt('max_references');
        $maxWords ??= $this->catalog->defaultInt('max_words');
        $maxOptions ??= $this->catalog->defaultInt('max_frontier_options');

        if ($maxReferences < 1 || $maxWords < 1 || $maxOptions < 1) {
            throw new ContextException('Reference, word, and frontier option limits must be positive integers.', 2);
        }

        $normalizedPaths = array_map($this->router->normalizePath(...), $paths);
        $normalizedPaths = array_values(array_unique($normalizedPaths));
        sort($normalizedPaths, SORT_STRING);
        $matches = [];

        foreach ($normalizedPaths as $path) {
            $rule = $this->router->match($path);
            $matches[] = [
                'path' => $path,
                'rule' => $rule['id'],
                'owner' => $rule['owner'],
                'priority' => $rule['priority'],
            ];
        }

        $canonicalOperation = $operation === null
            ? $this->router->inferOperation($normalizedPaths)
            : $this->router->canonicalOperation($operation);
        $operationSource = $operation === null
            ? ($canonicalOperation === null ? null : 'inferred')
            : 'explicit';

        if ($operationSource === 'inferred' && $canonicalOperation !== null && ! $this->router->operationApplied($matches, $canonicalOperation)) {
            $canonicalOperation = null;
            $operationSource = null;
        }
        $canonicalConcerns = array_map($this->router->canonicalConcern(...), $concerns);
        $canonicalConcerns = array_values(array_unique($canonicalConcerns));
        sort($canonicalConcerns, SORT_STRING);
        $expansions = array_values(array_unique($expansions));
        sort($expansions, SORT_STRING);
        $selected = [];
        $gateIds = $this->catalog->globalGates();

        foreach ($normalizedPaths as $path) {
            $rule = $this->router->match($path);

            foreach ($this->router->stringList($rule['references'] ?? [], "path rule {$rule['id']} references") as $reference) {
                $this->select($selected, $reference, "path {$path} ({$rule['owner']})");
            }

            foreach ($this->router->stringList($rule['gates'] ?? [], "path rule {$rule['id']} gates") as $gateId) {
                $gateIds[] = $gateId;
            }

            if ($canonicalOperation !== null && isset($rule['operation_set'])) {
                $references = $this->router->operationReferences((string) $rule['operation_set'], $canonicalOperation);

                foreach ($references as $reference) {
                    $this->select($selected, $reference, "operation {$canonicalOperation} for {$rule['owner']}");
                }
            }
        }

        if ($operationSource === 'explicit' && $canonicalOperation !== null && ! $this->router->operationApplied($matches, $canonicalOperation)) {
            throw new ContextException(
                "Operation {$canonicalOperation} is not available for the matched owners. Available: ".implode(', ', $this->router->availableOperations($matches)),
                3,
            );
        }

        foreach ($canonicalConcerns as $concernId) {
            $concern = $this->catalog->concerns()[$concernId] ?? null;

            if (! is_array($concern)) {
                throw new ContextException("Unknown concern: {$concernId}", 3);
            }

            if (isset($concern['references_by_owner_operation']) && $canonicalOperation === null) {
                throw new ContextException("Concern {$concernId} requires an explicit or safely inferred operation.", 3);
            }

            $applied = false;

            foreach ($matches as $match) {
                if (! $this->router->ownerAllowed((string) $match['owner'], $this->router->stringList($concern['owners'] ?? [], "concern {$concernId} owners"))) {
                    continue;
                }

                $applied = true;

                foreach ($this->router->stringList($concern['references'] ?? [], "concern {$concernId} references") as $reference) {
                    $this->select($selected, $reference, "concern {$concernId}");
                }

                $ownerReferences = $concern['references_by_owner'] ?? [];

                if (! is_array($ownerReferences)) {
                    throw new ContextException("Concern {$concernId} references_by_owner must be an object.");
                }

                foreach ($ownerReferences as $ownerPattern => $references) {
                    if ($this->router->ownerAllowed((string) $match['owner'], [(string) $ownerPattern])) {
                        foreach ($this->router->stringList($references, "concern {$concernId} owner references") as $reference) {
                            $this->select($selected, $reference, "concern {$concernId} for {$match['owner']}");
                        }
                    }
                }

                $ownerOperationReferences = $concern['references_by_owner_operation'] ?? [];

                if (! is_array($ownerOperationReferences)) {
                    throw new ContextException("Concern {$concernId} references_by_owner_operation must be an object.");
                }

                foreach ($ownerOperationReferences as $ownerPattern => $entries) {
                    if (! $this->router->ownerAllowed((string) $match['owner'], [(string) $ownerPattern])) {
                        continue;
                    }

                    if (! is_array($entries) || ! array_is_list($entries)) {
                        throw new ContextException("Concern {$concernId} owner-operation entries must be a list.");
                    }

                    foreach ($entries as $entry) {
                        if (! is_array($entry)) {
                            throw new ContextException("Concern {$concernId} owner-operation entry must be an object.");
                        }

                        $operations = $this->router->stringList($entry['operations'] ?? [], "concern {$concernId} owner operations");

                        if ($canonicalOperation !== null && in_array($canonicalOperation, $operations, true)) {
                            foreach ($this->router->stringList($entry['references'] ?? [], "concern {$concernId} owner-operation references") as $reference) {
                                $this->select($selected, $reference, "concern {$concernId} for {$match['owner']} {$canonicalOperation}");
                            }
                        }
                    }
                }

                foreach ($this->router->stringList($concern['gates'] ?? [], "concern {$concernId} gates") as $gateId) {
                    $gateIds[] = $gateId;
                }
            }

            if (! $applied) {
                throw new ContextException("Concern {$concernId} does not apply to the matched owners.", 3);
            }
        }

        $gateIds = array_values(array_unique($gateIds));
        sort($gateIds, SORT_STRING);
        $gates = $this->resolveGates($gateIds);

        foreach ($gates as $gate) {
            if ($gate['load']) {
                $this->select($selected, $gate['reference'], "gate {$gate['id']}");
            }
        }

        $this->frontier->applySelectors($selected, $selectors);

        if (count($selected) > $maxReferences) {
            throw new ContextException(
                sprintf('Selection has %d references, above --max-references=%d. Narrow the paths or concerns, or raise the explicit limit.', count($selected), $maxReferences),
                5,
            );
        }

        $references = [];
        $totalWords = 0;

        foreach ($selected as $relativePath => $reasons) {
            $absolutePath = $this->catalog->absoluteReference($relativePath);
            $content = (string) file_get_contents($absolutePath);
            $words = $this->wordCount($content);
            $totalWords += $words;
            $reference = [
                'path' => $relativePath,
                'title' => $this->graph->title($relativePath),
                'reasons' => array_values($reasons),
                'words' => $words,
                'sha256' => hash('sha256', $content),
            ];

            if ($includeContent) {
                $reference['content'] = $content;
            }

            $references[] = $reference;
        }

        if ($includeContent && $totalWords > $maxWords) {
            throw new ContextException(
                sprintf('Selected content has %d words, above --max-words=%d. Narrow the paths or concerns, or raise the explicit limit.', $totalWords, $maxWords),
                5,
            );
        }

        return [
            'schema_version' => 2,
            'inputs' => [
                'paths' => $normalizedPaths,
                'operation' => $canonicalOperation,
                'operation_source' => $operationSource,
                'concerns' => $canonicalConcerns,
                'selectors' => $selectors,
                'expansions' => $expansions,
            ],
            'matches' => $matches,
            'references' => $references,
            'gates' => $gates,
            'frontiers' => $this->frontier->groups($selected, $expansions, $offset, $maxOptions),
            'limits' => [
                'max_references' => $maxReferences,
                'max_words' => $maxWords,
                'max_frontier_options' => $maxOptions,
                'frontier_offset' => $offset,
                'selected_references' => count($references),
                'selected_words' => $totalWords,
                'content_included' => $includeContent,
            ],
        ];
    }

    /**
     * @param array<string, list<string>> $selected
     */
    private function select(array &$selected, string $reference, string $reason): void
    {
        $this->catalog->absoluteReference($reference);
        $selected[$reference] ??= [];
        $selected[$reference][$reason] = $reason;
    }

    /**
     * @param list<string> $gateIds
     * @return list<array{id: string, reference: string, anchor: string, load: bool}>
     */
    private function resolveGates(array $gateIds): array
    {
        $gates = [];

        foreach ($gateIds as $gateId) {
            $gate = $this->catalog->gates()[$gateId] ?? null;

            if (! is_array($gate) || ! is_string($gate['reference'] ?? null) || ! is_string($gate['anchor'] ?? null)) {
                throw new ContextException("Invalid or unknown gate: {$gateId}");
            }

            $this->catalog->absoluteReference($gate['reference']);

            if (! $this->graph->hasAnchor($gate['reference'], $gate['anchor'])) {
                throw new ContextException("Missing gate anchor: {$gate['reference']}#{$gate['anchor']}");
            }

            $gates[] = [
                'id' => $gateId,
                'reference' => $gate['reference'],
                'anchor' => $gate['anchor'],
                'load' => $gate['load'] ?? true,
            ];
        }

        return $gates;
    }

    private function wordCount(string $content): int
    {
        $words = preg_split('/\s+/u', trim($content), flags: PREG_SPLIT_NO_EMPTY);

        return is_array($words) ? count($words) : 0;
    }
}
