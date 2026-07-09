<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Frontier;
use LaravelProjectPatterns\Context\Validator;

$frontier = new Frontier($catalog, $graph);
$excluded = array_fill_keys($catalog->navigationExclusions(), true);
$graphEdges = 0;
$resolverEdges = 0;
$excludedEdges = 0;
$navigationParentEdges = 0;
$parents = 0;

$harness->same([], $graph->errors(), 'Markdown graph has structural errors.');

foreach ($graph->markdownFiles() as $parent) {
    $outgoing = $graph->outgoing($parent);
    $graphEdges += count($outgoing);
    $expected = [];

    foreach ($outgoing as $child) {
        if (isset($excluded[$child])) {
            $excludedEdges++;

            continue;
        }

        $expected[] = $child;
    }

    $expected = array_values(array_unique($expected));
    sort($expected, SORT_STRING);
    $actual = $frontier->availableChildren($parent);
    $harness->same($expected, $actual, "Frontier children drifted for {$parent}.");

    if (isset($excluded[$parent])) {
        $navigationParentEdges += count($expected);

        continue;
    }

    $resolverEdges += count($expected);

    $selected = [$parent => ['generated graph root' => 'generated graph root']];
    $groups = $frontier->groups($selected, [$parent], 0, max(1, count($expected)));
    $harness->same(1, count($groups), "Expanded parent {$parent} did not retain its frontier.");

    if ($groups === []) {
        continue;
    }

    $group = $groups[0];
    $harness->same($parent, $group['parent'], "Frontier parent drifted for {$parent}.");
    $harness->same(count($expected), $group['options_total'], "Frontier total drifted for {$parent}.");
    $harness->same($expected, array_column($group['options'], 'path'), "Expanded options drifted for {$parent}.");
    $harness->same('--expand='.$parent, $group['expand_argument'], "Expand argument drifted for {$parent}.");

    $paged = [];

    for ($offset = 0; $offset < count($expected); $offset += 3) {
        $page = $frontier->groups($selected, [$parent], $offset, 3)[0];
        $paged = [...$paged, ...array_column($page['options'], 'path')];
        $expectedHasMore = $offset + count($page['options']) < count($expected);
        $harness->same($expectedHasMore, $page['has_more'], "Pagination state drifted for {$parent} at {$offset}.");

        if ($expectedHasMore) {
            $harness->check(is_string($page['next_page_arguments']), "Pagination command missing for {$parent} at {$offset}.");
        }
    }

    $harness->same($expected, $paged, "Pagination lost or duplicated options for {$parent}.");

    foreach ($expected as $child) {
        $edgeSelection = $selected;
        $frontier->applySelectors($edgeSelection, [$child]);
        $harness->check(isset($edgeSelection[$child]), "Resolver edge {$parent} -> {$child} is not selectable.");
    }

    $parents++;
}

$rootSelections = [];

foreach ($catalog->pathRules() as $rule) {
    foreach ($rule['references'] as $reference) {
        $rootSelections[$reference] = ['generated task root' => 'generated task root'];
    }
}

$rootPaths = array_keys($rootSelections);
sort($rootPaths, SORT_STRING);
$rootGroups = $frontier->groups($rootSelections, $rootPaths, 0, 1);
$groupsByParent = array_column($rootGroups, null, 'parent');

foreach ($rootPaths as $parent) {
    $harness->check(isset($groupsByParent[$parent]), "Concurrent root {$parent} was starved.");

    if (isset($groupsByParent[$parent])) {
        $harness->check(count($groupsByParent[$parent]['options']) <= 1, "Concurrent root {$parent} ignored its own limit.");
    }
}

$factoryParent = 'references/database/factories/README.md';
$factoryChild = 'references/database/factories/patterns/core-rules.md';
$factoryGrandchild = 'references/database/factories/patterns/core-rules/state-methods.md';
$repeated = [
    $factoryParent => ['test' => 'test'],
    $factoryChild => ['test' => 'test'],
];
$harness->expectContextException(
    static fn () => $frontier->applySelectors($repeated, [$factoryChild]),
    'already loaded',
    3,
);
$harness->expectContextException(
    static fn () => $frontier->applySelectors($repeated, [$factoryGrandchild, $factoryParent]),
    'already loaded',
    3,
);
$harness->expectContextException(
    static fn () => $frontier->applySelectors($repeated, ['../outside.md']),
    'escapes the skill root',
    4,
);
$harness->expectContextException(
    static fn () => $frontier->groups($repeated, ['references/app/Models/README.md'], 0, 1),
    'is not loaded',
    3,
);
$harness->expectContextException(
    static fn () => $frontier->groups($repeated, [], -1, 1),
    'zero or greater',
    2,
);
$harness->expectContextException(
    static fn () => $frontier->groups($repeated, [], 0, 0),
    'positive integer',
    2,
);

$validation = (new Validator($catalog, $graph))->validate();
$harness->same([], $validation['errors'], 'Canonical exhaustive validator failed.');
$harness->same(464, $validation['metrics']['markdown_files'], 'Markdown baseline changed without reconciliation.');
$harness->same(455, $validation['metrics']['resolver_discoverable_markdown'], 'Resolver-discoverable baseline changed.');
$harness->same(1074, $validation['metrics']['resolver_edges'], 'Resolver-edge baseline changed.');
$harness->same(9, $validation['metrics']['navigation_exclusions'], 'Navigation-only classification changed.');
$harness->same(464, $validation['metrics']['resolver_discoverable_markdown'] + $validation['metrics']['navigation_exclusions'], 'Markdown classification is incomplete.');

$harness->metric('graph_markdown_nodes', count($graph->markdownFiles()));
$harness->metric('graph_all_edges', $graphEdges);
$harness->metric('graph_resolver_edges', $resolverEdges);
$harness->metric('graph_navigation_edges', $excludedEdges);
$harness->metric('graph_navigation_parent_edges', $navigationParentEdges);
$harness->metric('graph_parents_checked', $parents);
