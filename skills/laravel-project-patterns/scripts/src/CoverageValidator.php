<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

final class CoverageValidator
{
    public function __construct(
        private readonly Catalog $catalog,
        private readonly MarkdownGraph $graph,
    ) {}

    /**
     * @return array{errors: list<string>, metrics: array<string, int>}
     */
    public function validate(): array
    {
        $errors = [];
        $data = $this->catalog->data();
        $rules = is_array($data['path_rules'] ?? null) ? $data['path_rules'] : [];
        $sets = is_array($data['operation_sets'] ?? null) ? $data['operation_sets'] : [];
        $concerns = is_array($data['concerns'] ?? null) ? $data['concerns'] : [];
        $gates = is_array($data['gates'] ?? null) ? $data['gates'] : [];
        $coverage = is_array($data['coverage_cases'] ?? null) ? $data['coverage_cases'] : [];
        $seeds = [];
        $usedSets = [];
        $usedGates = [];
        $coveredRules = [];

        foreach ($coverage as $case) {
            if (! is_array($case) || ! is_string($case['rule'] ?? null) || ! is_string($case['path'] ?? null)) {
                continue;
            }

            if (isset($coveredRules[$case['rule']])) {
                $errors[] = "Path rule has duplicate coverage cases: {$case['rule']}.";
            }

            $coveredRules[$case['rule']] = true;
        }

        foreach ($rules as $rule) {
            if (! is_array($rule)) {
                continue;
            }

            foreach ($this->strings($rule['references'] ?? []) as $reference) {
                $seeds[$reference] = true;
            }

            foreach ($this->strings($rule['gates'] ?? []) as $gate) {
                $usedGates[$gate] = true;
            }

            if (is_string($rule['operation_set'] ?? null)) {
                $usedSets[$rule['operation_set']] = true;
            }
        }

        $operationEntries = 0;

        foreach ($sets as $setId => $operations) {
            if (! isset($usedSets[$setId])) {
                $errors[] = "Operation set has no path-rule owner: {$setId}.";
            }

            foreach (is_array($operations) ? $operations : [] as $references) {
                $operationEntries++;

                foreach ($this->strings($references) as $reference) {
                    $seeds[$reference] = true;
                }
            }
        }

        $concernOwnerPatterns = 0;
        $concernOwnerOperationEntries = 0;

        foreach ($concerns as $concern) {
            if (! is_array($concern)) {
                continue;
            }

            $concernOwnerPatterns += count($this->strings($concern['owners'] ?? []));

            foreach ($this->strings($concern['references'] ?? []) as $reference) {
                $seeds[$reference] = true;
            }

            foreach ($this->strings($concern['gates'] ?? []) as $gate) {
                $usedGates[$gate] = true;
            }

            foreach (is_array($concern['references_by_owner'] ?? null) ? $concern['references_by_owner'] : [] as $references) {
                foreach ($this->strings($references) as $reference) {
                    $seeds[$reference] = true;
                }
            }

            foreach (is_array($concern['references_by_owner_operation'] ?? null) ? $concern['references_by_owner_operation'] : [] as $entries) {
                foreach (is_array($entries) ? $entries : [] as $entry) {
                    if (! is_array($entry)) {
                        continue;
                    }

                    $concernOwnerOperationEntries++;

                    foreach ($this->strings($entry['references'] ?? []) as $reference) {
                        $seeds[$reference] = true;
                    }
                }
            }
        }

        $globalGates = $this->safeGlobalGates($errors);

        foreach ($globalGates as $gate) {
            $usedGates[$gate] = true;
        }

        foreach ($gates as $gateId => $gate) {
            if (! isset($usedGates[$gateId])) {
                $errors[] = "Gate is not used by a global, path, or concern contract: {$gateId}.";
            }

            if (is_array($gate) && is_string($gate['reference'] ?? null)) {
                $seeds[$gate['reference']] = true;
            }
        }

        foreach ($globalGates as $gate) {
            if (! isset($gates[$gate])) {
                $errors[] = "Global gate targets unknown gate {$gate}.";
            }
        }

        $exclusions = $this->safeNavigationExclusions($errors);
        $excluded = array_fill_keys($exclusions, true);
        $allMarkdown = array_fill_keys($this->graph->markdownFiles(), true);
        $rawReachable = array_fill_keys($this->graph->reachableFrom('SKILL.md'), true);

        foreach ($exclusions as $reference) {
            if (! isset($allMarkdown[$reference])) {
                $errors[] = "Navigation-only reference is not Markdown: {$reference}.";
            }

            if (! isset($rawReachable[$reference])) {
                $errors[] = "Navigation-only reference is unreachable from SKILL.md: {$reference}.";
            }
        }

        $reachable = $this->resolverReachable(array_keys($seeds), $excluded);

        foreach (array_keys($allMarkdown) as $reference) {
            if (! isset($reachable[$reference]) && ! isset($excluded[$reference])) {
                $errors[] = "Markdown is neither resolver-discoverable nor navigation-only: {$reference}.";
            }
        }

        $resolverEdges = 0;

        foreach (array_keys($reachable) as $parent) {
            foreach ($this->graph->outgoing($parent) as $child) {
                if (! isset($excluded[$child])) {
                    $resolverEdges++;
                }
            }
        }

        $errors = array_values(array_unique($errors));
        sort($errors, SORT_STRING);

        return [
            'errors' => $errors,
            'metrics' => [
                'operation_entries' => $operationEntries,
                'operation_aliases' => count($this->catalog->operationAliases()),
                'concern_aliases' => count($this->catalog->concernAliases()),
                'concern_owner_patterns' => $concernOwnerPatterns,
                'concern_owner_operation_entries' => $concernOwnerOperationEntries,
                'global_gates' => count($globalGates),
                'navigation_exclusions' => count($exclusions),
                'resolver_discoverable_markdown' => count($reachable),
                'resolver_edges' => $resolverEdges,
            ],
        ];
    }

    /**
     * @param list<string> $seeds
     * @param array<string, true> $excluded
     * @return array<string, true>
     */
    private function resolverReachable(array $seeds, array $excluded): array
    {
        $queue = $seeds;
        $reachable = [];

        while ($queue !== []) {
            $current = array_shift($queue);

            if (isset($reachable[$current]) || isset($excluded[$current])) {
                continue;
            }

            try {
                $this->catalog->absoluteReference($current);
            } catch (ContextException) {
                continue;
            }

            $reachable[$current] = true;

            foreach ($this->graph->outgoing($current) as $target) {
                if (! isset($excluded[$target])) {
                    $queue[] = $target;
                }
            }
        }

        return $reachable;
    }

    /** @return list<string> */
    private function safeGlobalGates(array &$errors): array
    {
        try {
            $gates = $this->catalog->globalGates();
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();

            return [];
        }

        return $this->uniqueStrings($gates, 'global_gates', $errors);
    }

    /** @return list<string> */
    private function safeNavigationExclusions(array &$errors): array
    {
        try {
            $references = $this->catalog->navigationExclusions();
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();

            return [];
        }

        return $this->uniqueStrings($references, 'navigation_exclusions', $errors);
    }

    /**
     * @param list<mixed> $values
     * @param list<string> $errors
     * @return list<string>
     */
    private function uniqueStrings(array $values, string $label, array &$errors): array
    {
        $strings = [];

        foreach ($values as $value) {
            if (! is_string($value) || $value === '') {
                $errors[] = "Catalog {$label} must contain non-empty strings.";

                continue;
            }

            if (isset($strings[$value])) {
                $errors[] = "Catalog {$label} contains duplicate {$value}.";
            }

            $strings[$value] = $value;
        }

        return array_values($strings);
    }

    /** @return list<string> */
    private function strings(mixed $value): array
    {
        if (! is_array($value) || ! array_is_list($value)) {
            return [];
        }

        return array_values(array_filter($value, is_string(...)));
    }
}
