<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Catalog;
use LaravelProjectPatterns\Context\MarkdownGraph;
use LaravelProjectPatterns\Context\Validator;

$validateData = static fn (array $data): array => (new Validator(
    Catalog::fromArray($skillRoot, $data),
    $graph,
))->validate();
$hasError = static function (array $result, string $message) use ($harness): void {
    $found = false;

    foreach ($result['errors'] as $error) {
        $found = $found || str_contains($error, $message);
    }

    $harness->check($found, "Validator did not report: {$message}");
};
$mutations = 0;

$invalid = $catalog->data();
$invalid['path_rules'][0]['references'] = ['references/missing.md'];
$hasError($validateData($invalid), 'Missing reference: references/missing.md');
$mutations++;

$duplicate = $catalog->data();
$duplicate['path_rules'][1]['id'] = $duplicate['path_rules'][0]['id'];
$hasError($validateData($duplicate), 'Duplicate path rule id');
$mutations++;

$malformed = $catalog->data();
$malformed['path_rules'] = 'invalid';
$hasError($validateData($malformed), 'Catalog path_rules must be a list');
$mutations++;

$escaping = $catalog->data();
$escaping['path_rules'][0]['references'] = ['../outside.md'];
$hasError($validateData($escaping), 'Reference escapes the skill root');
$mutations++;

$missingCoverage = $catalog->data();
$missingRule = array_pop($missingCoverage['coverage_cases'])['rule'];
$hasError($validateData($missingCoverage), "Path rule has no coverage case: {$missingRule}");
$mutations++;

$duplicateCoverage = $catalog->data();
$duplicateCoverage['coverage_cases'][] = $duplicateCoverage['coverage_cases'][0];
$hasError($validateData($duplicateCoverage), 'Path rule has duplicate coverage cases');
$mutations++;

$unusedSet = $catalog->data();
$unusedSet['operation_sets']['unused'] = ['update' => ['references/app/README.md']];
$hasError($validateData($unusedSet), 'Operation set has no path-rule owner: unused');
$mutations++;

$operationAlias = $catalog->data();
$operationAlias['operation_aliases']['invalid'] = 'missing-operation';
$hasError($validateData($operationAlias), 'targets unknown operation');
$mutations++;

$concernAlias = $catalog->data();
$concernAlias['concern_aliases']['invalid'] = 'missing-concern';
$hasError($validateData($concernAlias), 'targets unknown concern');
$mutations++;

$concernOwner = $catalog->data();
$concernOwner['concerns']['persistence']['owners'] = ['missing-owner'];
$hasError($validateData($concernOwner), 'owner pattern matches no path-rule owner');
$mutations++;

$globalGate = $catalog->data();
$globalGate['global_gates'][] = 'missing-gate';
$hasError($validateData($globalGate), 'Global gate targets unknown gate missing-gate');
$mutations++;

$duplicateGlobalGate = $catalog->data();
$duplicateGlobalGate['global_gates'][] = $duplicateGlobalGate['global_gates'][0];
$hasError($validateData($duplicateGlobalGate), 'global_gates contains duplicate');
$mutations++;

$unusedGate = $catalog->data();
$unusedGate['gates']['unused-gate'] = [
    'reference' => 'references/app/README.md',
    'anchor' => 'app',
    'load' => false,
];
$hasError($validateData($unusedGate), 'Gate is not used by a global, path, or concern contract');
$mutations++;

$missingAnchor = $catalog->data();
$missingAnchor['gates']['completion']['anchor'] = 'missing-anchor';
$hasError($validateData($missingAnchor), 'missing anchor');
$mutations++;

$missingDefault = $catalog->data();
unset($missingDefault['defaults']['max_frontier_options']);
$hasError($validateData($missingDefault), 'max_frontier_options must be a positive integer');
$mutations++;

$duplicateExclusion = $catalog->data();
$duplicateExclusion['navigation_exclusions'][] = $duplicateExclusion['navigation_exclusions'][0];
$hasError($validateData($duplicateExclusion), 'navigation_exclusions contains duplicate');
$mutations++;

$missingExclusion = $catalog->data();
$missingExclusion['navigation_exclusions'][] = 'references/missing-navigation.md';
$hasError($validateData($missingExclusion), 'Navigation-only reference is not Markdown');
$mutations++;

$temporaryRoot = sys_get_temp_dir().'/laravel-project-patterns-'.bin2hex(random_bytes(6));
mkdir($temporaryRoot.'/references', recursive: true);
file_put_contents($temporaryRoot.'/SKILL.md', "# Temporary Skill\n\n[Broken](references/missing.md)\n");
file_put_contents($temporaryRoot.'/references/orphan.md', "# Orphan\n");
$temporaryData = [
    'schema_version' => 1,
    'defaults' => [
        'max_references' => 1,
        'max_words' => 1,
        'max_frontier_options' => 1,
    ],
    'global_gates' => [],
    'navigation_exclusions' => ['SKILL.md'],
    'operation_aliases' => [],
    'concern_aliases' => [],
    'gates' => [],
    'operation_sets' => [],
    'concerns' => [],
    'path_rules' => [[
        'id' => 'temporary',
        'priority' => 1,
        'owner' => 'temporary',
        'patterns' => ['~^Temporary\.php$~'],
        'references' => ['SKILL.md'],
        'gates' => [],
    ]],
    'coverage_cases' => [[
        'rule' => 'temporary',
        'path' => 'Temporary.php',
    ]],
];
$temporaryResult = (new Validator(
    Catalog::fromArray($temporaryRoot, $temporaryData),
    new MarkdownGraph($temporaryRoot),
))->validate();
$hasError($temporaryResult, 'Missing link: SKILL.md -> references/missing.md');
$hasError($temporaryResult, 'neither resolver-discoverable nor navigation-only: references/orphan.md');
unlink($temporaryRoot.'/SKILL.md');
unlink($temporaryRoot.'/references/orphan.md');
rmdir($temporaryRoot.'/references');
rmdir($temporaryRoot);
$mutations++;

$canonical = (new Validator($catalog, $graph))->validate();
$harness->same([], $canonical['errors'], 'Canonical validator failed after mutation checks.');
$harness->same(53, $canonical['metrics']['path_rules'], 'Path-rule universe changed without reconciliation.');
$harness->same(48, $canonical['metrics']['operation_entries'], 'Operation-entry universe changed without reconciliation.');
$harness->same(13, $canonical['metrics']['concerns'], 'Concern universe changed without reconciliation.');
$harness->same(6, $canonical['metrics']['gates'], 'Gate universe changed without reconciliation.');

$harness->metric('validator_negative_mutations', $mutations);
