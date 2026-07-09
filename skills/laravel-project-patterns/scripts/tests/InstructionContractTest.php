<?php

declare(strict_types=1);

$skillInstructions = (string) file_get_contents($skillRoot.'/SKILL.md');
$agentInstructions = (string) file_get_contents($skillRoot.'/agents/openai.yaml');
$routerInstructions = (string) file_get_contents($skillRoot.'/references/context-resolver.md');
$instructionFiles = [
    'SKILL.md' => $skillInstructions,
    'agents/openai.yaml' => $agentInstructions,
    'references/context-resolver.md' => $routerInstructions,
];

foreach ($instructionFiles as $path => $content) {
    $lower = strtolower($content);
    $harness->check(preg_match('/plain\s+`?php`?/i', $content) === 1, "{$path} does not require plain php.");
    $harness->check(str_contains($lower, 'touched path'), "{$path} does not require touched paths.");
    $harness->check(str_contains($lower, 'reference'), "{$path} does not govern reference discovery.");
    $harness->check(str_contains($lower, 'edit'), "{$path} does not gate edits.");
    $harness->check(str_contains($lower, 'rg'), "{$path} does not prohibit manual rg discovery.");
    $harness->check(str_contains($lower, 'find'), "{$path} does not prohibit manual find discovery.");
    $harness->check(str_contains($lower, 'glob'), "{$path} does not prohibit glob discovery.");
    $harness->check(str_contains($lower, 'sed'), "{$path} does not prohibit broad sed discovery.");
    $harness->check(str_contains($lower, 'rerun'), "{$path} does not require a new preflight after path growth.");
    $harness->check(preg_match('/live\s+project[\s\S]{0,30}code/i', $content) === 1, "{$path} does not distinguish later live-code search.");
    $harness->check(! str_contains($lower, 'herd'), "{$path} contains an environment-specific PHP wrapper.");
    $harness->check(preg_match('/php\s*8\.[0-9]/i', $content) !== 1, "{$path} contains a PHP version gate.");
}

$harness->check(
    strpos($skillInstructions, 'Before any tool call') < strpos($skillInstructions, 'Read only exact references'),
    'SKILL.md does not put preflight before reference reads.',
);
$harness->check(
    strpos($skillInstructions, 'Read only exact references') < strpos($skillInstructions, 'Read the exact live project code files'),
    'SKILL.md does not put reference routing before live-code search.',
);
$harness->check(preg_match('/stale\s+results\s+do\s+not\s+authorize\s+the\s+new\s+surface/i', $skillInstructions) === 1, 'SKILL.md does not invalidate stale preflight output.');
$harness->check(str_contains($agentInstructions, 'scripts/context.php'), 'Agent prompt does not name the mandatory executable.');
$harness->check(str_contains($agentInstructions, '--expand'), 'Agent prompt does not require frontier expansion.');
$harness->check(str_contains($agentInstructions, '--select'), 'Agent prompt does not require returned child selection.');
$harness->check(str_contains($routerInstructions, '--max-options'), 'Router reference omits frontier limits.');
$harness->check(str_contains($routerInstructions, '--offset'), 'Router reference omits pagination.');
$harness->check(str_contains($routerInstructions, '--include-content'), 'Router reference omits exact-content opt-in.');
$harness->check(str_contains($routerInstructions, 'One branch can never consume or hide another'), 'Router reference omits starvation freedom.');

$harness->metric('instruction_contract_files', count($instructionFiles));
