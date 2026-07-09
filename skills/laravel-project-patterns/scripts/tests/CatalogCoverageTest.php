<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\PathRouter;
use LaravelProjectPatterns\Context\Renderer;

$catalogData = $catalog->data();
$fixture = json_decode(
    (string) file_get_contents(__DIR__.'/cases.json'),
    true,
    flags: JSON_THROW_ON_ERROR,
);
$coverageByRule = [];
$rulesById = [];
$seenGates = [];
$router = new PathRouter($catalog);

foreach ($catalogData['coverage_cases'] as $case) {
    $coverageByRule[$case['rule']] = $case['path'];
}

foreach ($catalog->pathRules() as $rule) {
    $rulesById[$rule['id']] = $rule;
}

$observeGates = static function (array $context) use ($catalog, $harness, &$seenGates): void {
    $references = array_column($context['references'], 'path');

    foreach ($context['gates'] as $gate) {
        $seenGates[$gate['id']] = true;
        $expected = $catalog->gates()[$gate['id']];
        $harness->same($expected['reference'], $gate['reference'], "Gate {$gate['id']} reference drifted.");
        $harness->same($expected['anchor'], $gate['anchor'], "Gate {$gate['id']} anchor drifted.");
        $harness->same($expected['load'], $gate['load'], "Gate {$gate['id']} load flag drifted.");

        if ($gate['load']) {
            $harness->check(in_array($gate['reference'], $references, true), "Loaded gate {$gate['id']} was not selected.");
        }
    }
};

foreach ($fixture['cases'] as $case) {
    $context = $resolver->resolve(
        paths: $case['paths'],
        operation: $case['operation'] ?? null,
        concerns: $case['concerns'],
        maxReferences: 1000,
    );
    $references = array_column($context['references'], 'path');
    $harness->same($case['expected_rule'], $context['matches'][0]['rule'], "{$case['id']}: wrong path rule.");
    $harness->same($case['expected_operation'], $context['inputs']['operation'], "{$case['id']}: wrong operation.");

    foreach ($case['required_references'] as $reference) {
        $harness->check(in_array($reference, $references, true), "{$case['id']}: missing {$reference}.");
    }

    foreach ($case['forbidden_references'] ?? [] as $reference) {
        $harness->check(! in_array($reference, $references, true), "{$case['id']}: unexpectedly selected {$reference}.");
    }

    $second = $resolver->resolve(
        paths: $case['paths'],
        operation: $case['operation'] ?? null,
        concerns: $case['concerns'],
        maxReferences: 1000,
    );
    $harness->same(Renderer::json($context), Renderer::json($second), "{$case['id']}: output is not deterministic.");
    $observeGates($context);
}

foreach ($rulesById as $ruleId => $rule) {
    $path = $coverageByRule[$ruleId] ?? null;
    $harness->check(is_string($path), "Path rule {$ruleId} has no generated fixture.");

    if (! is_string($path)) {
        continue;
    }

    $context = $resolver->resolve([$path], maxReferences: 1000);
    $references = array_column($context['references'], 'path');
    $harness->same($ruleId, $context['matches'][0]['rule'], "Fixture {$path} did not match {$ruleId}.");
    $harness->same($rule['owner'], $context['matches'][0]['owner'], "Fixture {$path} has wrong owner.");

    foreach ($rule['references'] as $reference) {
        $harness->check(in_array($reference, $references, true), "Rule {$ruleId} did not load {$reference}.");
    }

    $observeGates($context);
}

$operationApplications = 0;

foreach ($rulesById as $ruleId => $rule) {
    if (! isset($rule['operation_set'])) {
        continue;
    }

    foreach ($catalog->operationSets()[$rule['operation_set']] as $operation => $expectedReferences) {
        $context = $resolver->resolve([$coverageByRule[$ruleId]], operation: $operation, maxReferences: 1000);
        $references = array_column($context['references'], 'path');
        $harness->same($operation, $context['inputs']['operation'], "Rule {$ruleId} did not retain {$operation}.");
        $harness->same('explicit', $context['inputs']['operation_source'], "Rule {$ruleId} did not mark {$operation} explicit.");

        foreach ($expectedReferences as $reference) {
            $harness->check(in_array($reference, $references, true), "Operation {$ruleId}.{$operation} missed {$reference}.");
        }

        $observeGates($context);
        $operationApplications++;
    }
}

foreach ($catalog->operationAliases() as $alias => $target) {
    $applications = 0;

    foreach ($rulesById as $ruleId => $rule) {
        $set = isset($rule['operation_set']) ? $catalog->operationSets()[$rule['operation_set']] : [];

        if (! isset($set[$target])) {
            continue;
        }

        $context = $resolver->resolve([$coverageByRule[$ruleId]], operation: $alias, maxReferences: 1000);
        $harness->same($target, $context['inputs']['operation'], "Operation alias {$alias} did not resolve to {$target}.");
        $applications++;
    }

    $harness->check($applications > 0, "Operation alias {$alias} has no executable owner.");
}

$concernInvocations = [];
$concernApplications = 0;

foreach ($catalog->concerns() as $concernId => $concern) {
    foreach ($rulesById as $ruleId => $rule) {
        if (! $router->ownerAllowed($rule['owner'], $concern['owners'])) {
            continue;
        }

        $operations = [null];

        if (isset($concern['references_by_owner_operation'])) {
            $operations = [];

            foreach ($concern['references_by_owner_operation'] as $ownerPattern => $entries) {
                if (! $router->ownerAllowed($rule['owner'], [$ownerPattern])) {
                    continue;
                }

                foreach ($entries as $entry) {
                    foreach ($entry['operations'] as $operation) {
                        $set = isset($rule['operation_set']) ? $catalog->operationSets()[$rule['operation_set']] : [];

                        if (isset($set[$operation])) {
                            $operations[] = $operation;
                        }
                    }
                }
            }

            $operations = array_values(array_unique($operations));
        }

        foreach ($operations as $operation) {
            $context = $resolver->resolve(
                [$coverageByRule[$ruleId]],
                operation: $operation,
                concerns: [$concernId],
                maxReferences: 1000,
            );
            $references = array_column($context['references'], 'path');
            $expected = $concern['references'] ?? [];

            foreach ($concern['references_by_owner'] ?? [] as $ownerPattern => $ownerReferences) {
                if ($router->ownerAllowed($rule['owner'], [$ownerPattern])) {
                    $expected = [...$expected, ...$ownerReferences];
                }
            }

            foreach ($concern['references_by_owner_operation'] ?? [] as $ownerPattern => $entries) {
                if (! $router->ownerAllowed($rule['owner'], [$ownerPattern])) {
                    continue;
                }

                foreach ($entries as $entry) {
                    if ($operation !== null && in_array($operation, $entry['operations'], true)) {
                        $expected = [...$expected, ...$entry['references']];
                    }
                }
            }

            foreach (array_unique($expected) as $reference) {
                $harness->check(in_array($reference, $references, true), "Concern {$concernId} on {$ruleId} missed {$reference}.");
            }

            $concernInvocations[$concernId] ??= [$coverageByRule[$ruleId], $operation];
            $observeGates($context);
            $concernApplications++;
        }
    }
}

foreach ($catalog->concernAliases() as $alias => $target) {
    [$path, $operation] = $concernInvocations[$target];
    $aliased = $resolver->resolve([$path], operation: $operation, concerns: [$alias], maxReferences: 1000);
    $canonical = $resolver->resolve([$path], operation: $operation, concerns: [$target], maxReferences: 1000);
    $harness->same(Renderer::json($canonical), Renderer::json($aliased), "Concern alias {$alias} did not equal {$target}.");
}

foreach (array_keys($catalog->gates()) as $gateId) {
    $harness->check(isset($seenGates[$gateId]), "Gate {$gateId} has no executable coverage.");
}

$harness->metric('catalog_path_rules', count($rulesById));
$harness->metric('catalog_operation_applications', $operationApplications);
$harness->metric('catalog_concern_applications', $concernApplications);
