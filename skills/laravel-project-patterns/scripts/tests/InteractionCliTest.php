<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Catalog;
use LaravelProjectPatterns\Context\Renderer;
use LaravelProjectPatterns\Context\Resolver;

$coveragePaths = array_column($catalog->data()['coverage_cases'], 'path');
$pairOrders = 0;

for ($left = 0; $left < count($coveragePaths); $left++) {
    for ($right = $left + 1; $right < count($coveragePaths); $right++) {
        $forward = $resolver->resolve([$coveragePaths[$left], $coveragePaths[$right]], maxReferences: 1000);
        $reverse = $resolver->resolve([$coveragePaths[$right], $coveragePaths[$left]], maxReferences: 1000);
        $harness->same(
            Renderer::json($forward),
            Renderer::json($reverse),
            "Path order changed {$coveragePaths[$left]} + {$coveragePaths[$right]}.",
        );
        $pairOrders += 2;
    }
}

$allRules = $resolver->resolve($coveragePaths, maxReferences: 1000);
$harness->same(count($coveragePaths), count($allRules['matches']), 'All-rules preflight lost matched paths.');
$expectedRuleIds = array_values(array_unique(array_column($catalog->data()['coverage_cases'], 'rule')));
$actualRuleIds = array_values(array_unique(array_column($allRules['matches'], 'rule')));
sort($expectedRuleIds, SORT_STRING);
sort($actualRuleIds, SORT_STRING);
$harness->same($expectedRuleIds, $actualRuleIds, 'All-rules preflight lost rule identities.');

$growingPaths = [];

foreach ($coveragePaths as $index => $path) {
    $growingPaths[] = $path;
    $context = $resolver->resolve($growingPaths, maxReferences: 1000);
    $rerun = $resolver->resolve(array_reverse($growingPaths), maxReferences: 1000);
    $harness->same($index + 1, count($context['matches']), "Path-set expansion lost {$path}.");
    $harness->same(Renderer::json($context), Renderer::json($rerun), "Expanded path set is order-dependent at {$path}.");
}

$fivePaths = [
    'app/Http/Requests/StorePackageRequest.php',
    'database/factories/PackageFactory.php',
    'tests/Feature/Http/Controllers/StorePackageControllerTest.php',
    'tests/Feature/PageProperties/PackagePagePropertiesTest.php',
    'tests/Integration/Http/Resources/PackageResourceTest.php',
];
$five = $resolver->resolve($fivePaths, maxReferences: 1000);
$fiveRules = array_column($five['matches'], 'rule');
$harness->same(
    ['app-form-request', 'database-factory', 'test-feature-controller', 'test-generic', 'test-integration-resource'],
    $fiveRules,
    'Five-surface regression matched the wrong rules.',
);
$fiveParents = array_column($five['frontiers'], 'parent');
$requiredParents = [
    'references/app/Http/Requests/README.md',
    'references/database/factories/README.md',
    'references/tests/Feature/Http/Controllers/README.md',
    'references/tests/README.md',
    'references/tests/Integration/Http/Resources/README.md',
];

foreach ($requiredParents as $parent) {
    $harness->check(in_array($parent, $fiveParents, true), "Five-surface frontier hid {$parent}.");
}

$expandedFive = $resolver->resolve(
    $fivePaths,
    expansions: $requiredParents,
    maxReferences: 1000,
    maxOptions: 100,
);
$selectors = [];
$usedTargets = array_fill_keys(array_column($expandedFive['references'], 'path'), true);
$expandedByParent = array_column($expandedFive['frontiers'], null, 'parent');

foreach ($requiredParents as $parent) {
    foreach ($expandedByParent[$parent]['options'] as $option) {
        if (! isset($usedTargets[$option['path']])) {
            $selectors[] = $option['path'];
            $usedTargets[$option['path']] = true;

            break;
        }
    }
}

$harness->same(count($requiredParents), count($selectors), 'Could not derive one unique child for every regression branch.');
$walkedFive = $resolver->resolve($fivePaths, selectors: $selectors, maxReferences: 1000);
$walkedReferences = array_column($walkedFive['references'], 'path');

foreach ($selectors as $selector) {
    $harness->check(in_array($selector, $walkedReferences, true), "Multi-branch walk lost {$selector}.");
}

$schema = $resolver->resolve(
    ['app/Models/Record.php'],
    expansions: ['references/app/Models/README.md'],
    maxOptions: 2,
);
$harness->same(
    ['schema_version', 'inputs', 'matches', 'references', 'gates', 'frontiers', 'limits'],
    array_keys($schema),
    'Context schema keys drifted.',
);
$harness->same(
    ['paths', 'operation', 'operation_source', 'concerns', 'selectors', 'expansions'],
    array_keys($schema['inputs']),
    'Input schema keys drifted.',
);
$harness->same(['path', 'rule', 'owner', 'priority'], array_keys($schema['matches'][0]), 'Match schema keys drifted.');
$harness->same(['path', 'title', 'reasons', 'words', 'sha256'], array_keys($schema['references'][0]), 'Reference schema keys drifted.');
$harness->same(['id', 'reference', 'anchor', 'load'], array_keys($schema['gates'][0]), 'Gate schema keys drifted.');
$harness->same(
    ['parent', 'title', 'options_total', 'expanded', 'offset', 'limit', 'has_more', 'expand_argument', 'next_page_arguments', 'options'],
    array_keys($schema['frontiers'][0]),
    'Frontier schema keys drifted.',
);
$expandedGroup = array_values(array_filter($schema['frontiers'], static fn (array $group): bool => $group['expanded']))[0];
$harness->same(['path', 'title', 'select_argument'], array_keys($expandedGroup['options'][0]), 'Frontier option schema keys drifted.');
$harness->same(
    ['max_references', 'max_words', 'max_frontier_options', 'frontier_offset', 'selected_references', 'selected_words', 'content_included'],
    array_keys($schema['limits']),
    'Limit schema keys drifted.',
);

$content = $resolver->resolve(['app/Models/Record.php'], includeContent: true, maxWords: 5000);

foreach ($content['references'] as $reference) {
    $expected = (string) file_get_contents($skillRoot.'/'.$reference['path']);
    $harness->same($expected, $reference['content'], "Exact content drifted for {$reference['path']}.");
    $harness->same(hash('sha256', $expected), $reference['sha256'], "Content hash drifted for {$reference['path']}.");
}

$markdown = Renderer::markdown($schema);
$harness->check(str_contains($markdown, '## Reference Frontiers'), 'Markdown omitted frontier section.');
$harness->check(str_contains($markdown, '--expand=references/app/Models/README.md'), 'Markdown omitted expansion command.');
$harness->check(str_contains($markdown, '--select='), 'Markdown omitted selection command.');

$harness->expectContextException(static fn () => $resolver->resolve([]), 'At least one --path', 2);
$harness->expectContextException(static fn () => $resolver->resolve(['src/Unknown.php']), 'No catalog path rule matches', 3);
$harness->expectContextException(static fn () => $resolver->resolve(['../app/Models/Record.php']), 'Path traversal', 3);
$harness->expectContextException(static fn () => $resolver->resolve(['app/Models/Record.php'], maxReferences: 1), 'above --max-references', 5);
$harness->expectContextException(static fn () => $resolver->resolve(['app/Models/Record.php'], includeContent: true, maxWords: 1), 'above --max-words', 5);
$harness->expectContextException(static fn () => $resolver->resolve(['app/Actions/Aggregate.php'], concerns: ['aggregate']), 'requires an explicit', 3);
$harness->expectContextException(static fn () => $resolver->resolve(['app/Models/Record.php'], operation: 'unknown'), 'not available', 3);
$harness->expectContextException(static fn () => $resolver->resolve(['app/Models/Record.php'], concerns: ['unknown']), 'Unknown concern', 3);

$absolute = $resolver->resolve(['/workspace/project/app/Models/Record.php']);
$windows = $resolver->resolve(['C:\\project\\database\\factories\\RecordFactory.php']);
$harness->same('app-model', $absolute['matches'][0]['rule'], 'Absolute Linux path did not normalize.');
$harness->same('database-factory', $windows['matches'][0]['rule'], 'Windows path did not normalize.');

$ambiguousData = $catalog->data();
$ambiguousData['path_rules'] = [
    ['id' => 'one', 'priority' => 10, 'owner' => 'one', 'patterns' => ['~^ambiguous\.php$~'], 'references' => ['references/app/README.md'], 'gates' => []],
    ['id' => 'two', 'priority' => 10, 'owner' => 'two', 'patterns' => ['~^ambiguous\.php$~'], 'references' => ['references/app/README.md'], 'gates' => []],
];
$ambiguous = new Resolver(Catalog::fromArray($skillRoot, $ambiguousData), $graph);
$harness->expectContextException(static fn () => $ambiguous->resolve(['ambiguous.php']), 'Ambiguous path', 3);

$validJson = $harness->runCli([
    '--path=app/Models/Record.php',
    '--expand=references/app/Models/README.md',
    '--max-options=1',
    '--format=json',
]);
$harness->same(0, $validJson['exit_code'], 'CLI JSON command failed.');
$decoded = json_decode($validJson['output'], true, flags: JSON_THROW_ON_ERROR);
$harness->same(2, $decoded['schema_version'], 'CLI JSON schema version drifted.');
$harness->same(1, $decoded['limits']['max_frontier_options'], 'CLI option limit was ignored.');

$validMarkdown = $harness->runCli(['--path=app/Models/Record.php', '--format=markdown']);
$harness->same(0, $validMarkdown['exit_code'], 'CLI Markdown command failed.');
$harness->check(str_contains($validMarkdown['output'], '# Laravel Project Pattern Context'), 'CLI Markdown heading is missing.');

$listing = $harness->runCli(['--list', '--format=json']);
$harness->same(0, $listing['exit_code'], 'CLI list failed.');
$listed = json_decode($listing['output'], true, flags: JSON_THROW_ON_ERROR);
$harness->same(count($catalog->pathRules()), count($listed['path_rules']), 'CLI list omitted path rules.');
$harness->same(0, $harness->runCli(['--help'])['exit_code'], 'CLI help failed.');

$cliFailures = [
    [[], 2, 'At least one --path'],
    [['--path=app/Models/Record.php', '--format=xml'], 2, 'Format must be'],
    [['--path=app/Models/Record.php', '--offset=nope'], 2, 'must be integers'],
    [['--path=app/Models/Record.php', '--max-options=0'], 2, 'positive integers'],
    [['--path=src/Unknown.php'], 3, 'No catalog path rule'],
    [['--path=../app/Models/Record.php'], 3, 'Path traversal'],
    [['--path=app/Models/Record.php', '--operation=unknown'], 3, 'not available'],
    [['--path=app/Models/Record.php', '--concern=unknown'], 3, 'Unknown concern'],
    [['--path=app/Models/Record.php', '--select=references/missing.md'], 4, 'Missing reference'],
    [['--path=app/Models/Record.php', '--expand=references/app/Actions/README.md'], 3, 'is not loaded'],
    [['--path=app/Models/Record.php', '--max-references=1'], 5, 'above --max-references'],
    [['--path=app/Models/Record.php', '--include-content', '--max-words=1'], 5, 'above --max-words'],
];

foreach ($cliFailures as [$arguments, $exitCode, $message]) {
    $result = $harness->runCli($arguments);
    $harness->same($exitCode, $result['exit_code'], 'CLI returned the wrong exit code for '.implode(' ', $arguments));
    $harness->check(str_contains($result['output'], $message), 'CLI returned the wrong error for '.implode(' ', $arguments));
}

$harness->metric('interaction_pair_orders', $pairOrders);
$harness->metric('interaction_path_expansions', count($growingPaths));
$harness->metric('interaction_cli_failure_modes', count($cliFailures));
