<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

final class Validator
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
        $errors = $this->graph->errors();
        $data = $this->catalog->data();

        if (($data['schema_version'] ?? null) !== 1) {
            $errors[] = 'Catalog schema_version must be 1.';
        }

        try {
            foreach (['max_references', 'max_words', 'max_frontier_options'] as $key) {
                $this->catalog->defaultInt($key);
            }
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }

        $gateIds = [];

        try {
            foreach ($this->catalog->gates() as $gateId => $gate) {
                $gateIds[$gateId] = true;

                if (! is_array($gate) || ! is_string($gate['reference'] ?? null) || ! is_string($gate['anchor'] ?? null) || ! is_bool($gate['load'] ?? null)) {
                    $errors[] = "Gate {$gateId} must define string reference, string anchor, and boolean load.";

                    continue;
                }

                $this->validateReference($gate['reference'], $errors);

                if (! $this->graph->hasAnchor($gate['reference'], $gate['anchor'])) {
                    $errors[] = "Gate {$gateId} has missing anchor {$gate['reference']}#{$gate['anchor']}.";
                }
            }
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }

        $operationSetIds = [];

        try {
            foreach ($this->catalog->operationSets() as $setId => $operations) {
                $operationSetIds[$setId] = true;

                if (! is_array($operations) || $operations === []) {
                    $errors[] = "Operation set {$setId} must be a non-empty object.";

                    continue;
                }

                foreach ($operations as $operation => $references) {
                    $this->validateStringList($references, "operation {$setId}.{$operation}", $errors, validateReferences: true);
                }
            }
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }

        $concernIds = [];

        try {
            foreach ($this->catalog->concerns() as $concernId => $concern) {
                $concernIds[$concernId] = true;

                if (! is_array($concern)) {
                    $errors[] = "Concern {$concernId} must be an object.";

                    continue;
                }

                $this->validateStringList($concern['owners'] ?? null, "concern {$concernId} owners", $errors);
                $this->validateStringList($concern['references'] ?? [], "concern {$concernId} references", $errors, validateReferences: true, allowEmpty: true);
                $this->validateGateList($concern['gates'] ?? [], "concern {$concernId}", $gateIds, $errors);
                $ownerReferences = $concern['references_by_owner'] ?? [];

                if (! is_array($ownerReferences)) {
                    $errors[] = "Concern {$concernId} references_by_owner must be an object.";
                } else {
                    foreach ($ownerReferences as $owner => $references) {
                        $this->validateStringList($references, "concern {$concernId} owner {$owner}", $errors, validateReferences: true);
                    }
                }

                $ownerOperationReferences = $concern['references_by_owner_operation'] ?? [];

                if (! is_array($ownerOperationReferences)) {
                    $errors[] = "Concern {$concernId} references_by_owner_operation must be an object.";
                } else {
                    foreach ($ownerOperationReferences as $owner => $entries) {
                        if (! is_array($entries) || ! array_is_list($entries) || $entries === []) {
                            $errors[] = "Concern {$concernId} owner-operation {$owner} must be a non-empty list.";

                            continue;
                        }

                        foreach ($entries as $entry) {
                            if (! is_array($entry)) {
                                $errors[] = "Concern {$concernId} owner-operation {$owner} entry must be an object.";

                                continue;
                            }

                            $this->validateStringList($entry['operations'] ?? null, "concern {$concernId} owner-operation {$owner} operations", $errors);
                            $this->validateStringList($entry['references'] ?? null, "concern {$concernId} owner-operation {$owner} references", $errors, validateReferences: true);
                        }
                    }
                }
            }
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }

        foreach ($this->catalog->concernAliases() as $alias => $target) {
            if (! isset($concernIds[$target])) {
                $errors[] = "Concern alias {$alias} targets unknown concern {$target}.";
            }
        }

        $ruleIds = [];
        $ruleOwners = [];
        $coverageCount = 0;

        try {
            foreach ($this->catalog->pathRules() as $index => $rule) {
                if (! is_string($rule['id'] ?? null) || $rule['id'] === '') {
                    $errors[] = "Path rule at index {$index} has no valid id.";

                    continue;
                }

                $ruleId = $rule['id'];

                if (isset($ruleIds[$ruleId])) {
                    $errors[] = "Duplicate path rule id: {$ruleId}.";
                }

                $ruleIds[$ruleId] = true;

                if (! is_int($rule['priority'] ?? null)) {
                    $errors[] = "Path rule {$ruleId} priority must be an integer.";
                }

                if (! is_string($rule['owner'] ?? null) || $rule['owner'] === '') {
                    $errors[] = "Path rule {$ruleId} owner must be a non-empty string.";
                } else {
                    $ruleOwners[$rule['owner']] = true;
                }

                $patterns = $rule['patterns'] ?? null;
                $this->validateStringList($patterns, "path rule {$ruleId} patterns", $errors);

                if (is_array($patterns)) {
                    foreach ($patterns as $pattern) {
                        if (is_string($pattern) && @preg_match($pattern, '') === false) {
                            $errors[] = "Path rule {$ruleId} has invalid regex {$pattern}.";
                        }
                    }
                }

                $this->validateStringList($rule['references'] ?? null, "path rule {$ruleId} references", $errors, validateReferences: true);
                $this->validateGateList($rule['gates'] ?? [], "path rule {$ruleId}", $gateIds, $errors);

                if (isset($rule['operation_set']) && ! isset($operationSetIds[$rule['operation_set']])) {
                    $errors[] = "Path rule {$ruleId} uses unknown operation set {$rule['operation_set']}.";
                }
            }
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }

        $knownOperations = [];

        foreach ($this->catalog->operationSets() as $operations) {
            $knownOperations = [...$knownOperations, ...array_keys($operations)];
        }

        $knownOperations = array_values(array_unique($knownOperations));
        $pathRouter = new PathRouter($this->catalog);

        foreach ($this->catalog->concerns() as $concernId => $concern) {
            if (! is_array($concern)) {
                continue;
            }

            $ownerPatterns = is_array($concern['owners'] ?? null) ? $concern['owners'] : [];

            foreach ($ownerPatterns as $ownerPattern) {
                $matchesKnownOwner = false;

                foreach (array_keys($ruleOwners) as $owner) {
                    $matchesKnownOwner = $matchesKnownOwner || $pathRouter->ownerAllowed($owner, [$ownerPattern]);
                }

                if (! $matchesKnownOwner) {
                    $errors[] = "Concern {$concernId} owner pattern matches no path-rule owner: {$ownerPattern}.";
                }
            }

            foreach (array_keys($concern['references_by_owner'] ?? []) as $ownerPattern) {
                $ownerKeyAllowed = false;

                foreach (array_keys($ruleOwners) as $owner) {
                    $ownerKeyAllowed = $ownerKeyAllowed
                        || ($pathRouter->ownerAllowed($owner, [(string) $ownerPattern])
                            && $pathRouter->ownerAllowed($owner, $ownerPatterns));
                }

                if (! $ownerKeyAllowed) {
                    $errors[] = "Concern {$concernId} owner-reference key is outside its owners: {$ownerPattern}.";
                }
            }

            foreach (($concern['references_by_owner_operation'] ?? []) as $ownerPattern => $entries) {
                $ownerKeyAllowed = false;

                foreach (array_keys($ruleOwners) as $owner) {
                    $ownerKeyAllowed = $ownerKeyAllowed
                        || ($pathRouter->ownerAllowed($owner, [(string) $ownerPattern])
                            && $pathRouter->ownerAllowed($owner, $ownerPatterns));
                }

                if (! $ownerKeyAllowed) {
                    $errors[] = "Concern {$concernId} owner-operation key is outside its owners: {$ownerPattern}.";
                }

                foreach (is_array($entries) ? $entries : [] as $entry) {
                    foreach (is_array($entry['operations'] ?? null) ? $entry['operations'] : [] as $operation) {
                        if (! in_array($operation, $knownOperations, true)) {
                            $errors[] = "Concern {$concernId} uses unknown operation {$operation}.";
                        }
                    }
                }
            }
        }

        $coverageCases = $data['coverage_cases'] ?? null;
        $coveredRules = [];

        if (! is_array($coverageCases) || ! array_is_list($coverageCases)) {
            $errors[] = 'Catalog coverage_cases must be a list.';
        } else {
            foreach ($coverageCases as $index => $case) {
                if (! is_array($case) || ! is_string($case['rule'] ?? null) || ! is_string($case['path'] ?? null)) {
                    $errors[] = "Coverage case at index {$index} must define string rule and path.";

                    continue;
                }

                $coverageCount++;
                $expectedRule = $case['rule'];
                $coveredRules[$expectedRule] = true;

                if (! isset($ruleIds[$expectedRule])) {
                    $errors[] = "Coverage case {$case['path']} targets unknown rule {$expectedRule}.";

                    continue;
                }

                try {
                    $matchedRule = $pathRouter->match($pathRouter->normalizePath($case['path']));

                    if (($matchedRule['id'] ?? null) !== $expectedRule) {
                        $errors[] = "Coverage case {$case['path']} expected {$expectedRule}, matched {$matchedRule['id']}.";
                    }
                } catch (ContextException $exception) {
                    $errors[] = "Coverage case {$case['path']} failed: {$exception->getMessage()}";
                }
            }
        }

        foreach (array_keys($ruleIds) as $ruleId) {
            if (! isset($coveredRules[$ruleId])) {
                $errors[] = "Path rule has no coverage case: {$ruleId}.";
            }
        }

        foreach ($this->catalog->operationAliases() as $alias => $target) {
            $known = false;

            foreach ($this->catalog->operationSets() as $operations) {
                $known = $known || isset($operations[$target]);
            }

            if (! $known) {
                $errors[] = "Operation alias {$alias} targets unknown operation {$target}.";
            }
        }

        $reachable = $this->graph->reachableFrom('SKILL.md');
        $unreachable = array_values(array_diff($this->graph->markdownFiles(), $reachable));

        foreach ($unreachable as $relativePath) {
            $errors[] = "Unreachable Markdown: {$relativePath}.";
        }

        $coverage = (new CoverageValidator($this->catalog, $this->graph))->validate();
        $errors = [...$errors, ...$coverage['errors']];
        $errors = array_values(array_unique($errors));
        sort($errors, SORT_STRING);

        return [
            'errors' => $errors,
            'metrics' => [...[
                'path_rules' => count($ruleIds),
                'owners' => count($ruleOwners),
                'coverage_cases' => $coverageCount,
                'operation_sets' => count($operationSetIds),
                'concerns' => count($concernIds),
                'gates' => count($gateIds),
                'markdown_files' => count($this->graph->markdownFiles()),
                'reachable_markdown' => count($reachable),
            ], ...$coverage['metrics']],
        ];
    }

    /**
     * @param list<string> $errors
     */
    private function validateReference(string $reference, array &$errors): void
    {
        try {
            $this->catalog->absoluteReference($reference);
        } catch (ContextException $exception) {
            $errors[] = $exception->getMessage();
        }
    }

    /**
     * @param list<string> $errors
     */
    private function validateStringList(
        mixed $value,
        string $label,
        array &$errors,
        bool $validateReferences = false,
        bool $allowEmpty = false,
    ): void
    {
        if (! is_array($value) || ! array_is_list($value) || ($value === [] && ! $allowEmpty)) {
            $errors[] = ucfirst($label).' must be a non-empty list.';

            return;
        }

        foreach ($value as $item) {
            if (! is_string($item) || $item === '') {
                $errors[] = ucfirst($label).' must contain non-empty strings.';

                continue;
            }

            if ($validateReferences) {
                $this->validateReference($item, $errors);
            }
        }
    }

    /**
     * @param array<string, true> $gateIds
     * @param list<string> $errors
     */
    private function validateGateList(mixed $gates, string $label, array $gateIds, array &$errors): void
    {
        if (! is_array($gates) || ! array_is_list($gates)) {
            $errors[] = ucfirst($label).' gates must be a list.';

            return;
        }

        foreach ($gates as $gateId) {
            if (! is_string($gateId) || ! isset($gateIds[$gateId])) {
                $errors[] = ucfirst($label)." uses unknown gate {$gateId}.";
            }
        }
    }
}
