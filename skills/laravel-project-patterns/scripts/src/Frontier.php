<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

final class Frontier
{
    public function __construct(
        private readonly Catalog $catalog,
        private readonly MarkdownGraph $graph,
    ) {}

    /**
     * @param array<string, array<string, string>> $selected
     * @param list<string> $selectors
     */
    public function applySelectors(array &$selected, array $selectors): void
    {
        foreach ($selectors as $selector) {
            if (! is_string($selector) || $selector === '') {
                throw new ContextException('Every --select value must be a non-empty reference path.', 3);
            }

            $this->catalog->absoluteReference($selector);

            if (isset($selected[$selector])) {
                throw new ContextException("Selected reference is already loaded: {$selector}", 3);
            }

            $selectedPaths = array_keys($selected);
            $parents = [];

            foreach ($selectedPaths as $parent) {
                if (in_array($selector, $this->availableChildren($parent, $selectedPaths), true)) {
                    $parents[] = $parent;
                }
            }

            if ($parents === []) {
                throw new ContextException("Selected reference is not an immediate child of any loaded reference: {$selector}", 3);
            }

            sort($parents, SORT_STRING);
            $reason = 'explicit child of '.implode(', ', $parents);
            $selected[$selector] = [$reason => $reason];
        }
    }

    /**
     * @param array<string, array<string, string>> $selected
     * @param list<string> $expansions
     * @return list<array<string, mixed>>
     */
    public function groups(
        array $selected,
        array $expansions,
        int $offset,
        int $limit,
    ): array {
        if ($offset < 0) {
            throw new ContextException('Frontier offset must be zero or greater.', 2);
        }

        if ($limit < 1) {
            throw new ContextException('Frontier option limit must be a positive integer.', 2);
        }

        $expansions = array_values(array_unique($expansions));
        sort($expansions, SORT_STRING);

        foreach ($expansions as $parent) {
            if (! is_string($parent) || $parent === '') {
                throw new ContextException('Every --expand value must be a non-empty reference path.', 3);
            }

            $this->catalog->absoluteReference($parent);

            if (! isset($selected[$parent])) {
                throw new ContextException("Expanded reference is not loaded: {$parent}", 3);
            }
        }

        $selectedPaths = array_keys($selected);
        $parents = $selectedPaths;
        sort($parents, SORT_STRING);
        $groups = [];

        foreach ($parents as $parent) {
            $children = $this->availableChildren($parent, $selectedPaths);
            $expanded = in_array($parent, $expansions, true);

            if ($children === [] && ! $expanded) {
                continue;
            }

            $page = $expanded ? array_slice($children, $offset, $limit) : [];
            $options = array_map(
                fn (string $path): array => [
                    'path' => $path,
                    'title' => $this->graph->title($path),
                    'select_argument' => '--select='.$path,
                ],
                $page,
            );
            $hasMore = $expanded && $offset + count($page) < count($children);

            $groups[] = [
                'parent' => $parent,
                'title' => $this->graph->title($parent),
                'options_total' => count($children),
                'expanded' => $expanded,
                'offset' => $expanded ? $offset : null,
                'limit' => $expanded ? $limit : null,
                'has_more' => $expanded ? $hasMore : null,
                'expand_argument' => '--expand='.$parent,
                'next_page_arguments' => $hasMore
                    ? sprintf('--expand=%s --offset=%d --max-options=%d', $parent, $offset + $limit, $limit)
                    : null,
                'options' => $options,
            ];
        }

        return $groups;
    }

    /**
     * @param list<string> $selectedPaths
     * @return list<string>
     */
    public function availableChildren(string $parent, array $selectedPaths = []): array
    {
        $excluded = $this->catalog->navigationExclusions();
        $children = [];

        foreach ($this->graph->outgoing($parent) as $target) {
            if (in_array($target, $excluded, true) || in_array($target, $selectedPaths, true)) {
                continue;
            }

            $children[$target] = $target;
        }

        $children = array_values($children);
        sort($children, SORT_STRING);

        return $children;
    }
}
