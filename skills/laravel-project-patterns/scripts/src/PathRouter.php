<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

final class PathRouter
{
    public function __construct(private readonly Catalog $catalog) {}

    /**
     * @return array<string, mixed>
     */
    public function match(string $path): array
    {
        $matches = [];

        foreach ($this->catalog->pathRules() as $rule) {
            $patterns = $this->stringList($rule['patterns'] ?? [], 'path rule patterns');

            foreach ($patterns as $pattern) {
                $result = @preg_match($pattern, $path);

                if ($result === false) {
                    throw new ContextException("Invalid path-rule regex in {$rule['id']}: {$pattern}");
                }

                if ($result === 1) {
                    $matches[] = $rule;

                    break;
                }
            }
        }

        if ($matches === []) {
            throw new ContextException("No catalog path rule matches: {$path}", 3);
        }

        usort($matches, static fn (array $left, array $right): int => ($right['priority'] ?? 0) <=> ($left['priority'] ?? 0));
        $highestPriority = $matches[0]['priority'] ?? 0;
        $highest = array_values(array_filter(
            $matches,
            static fn (array $rule): bool => ($rule['priority'] ?? 0) === $highestPriority,
        ));

        if (count($highest) > 1) {
            $ids = array_map(static fn (array $rule): string => (string) $rule['id'], $highest);

            throw new ContextException("Ambiguous path {$path}; equal-priority rules: ".implode(', ', $ids), 3);
        }

        return $highest[0];
    }

    public function normalizePath(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path));

        if ($path === '' || str_contains($path, "\0")) {
            throw new ContextException('Input paths must be non-empty and contain no null bytes.', 3);
        }

        $segments = [];

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                throw new ContextException("Path traversal is not allowed: {$path}", 3);
            }

            $segments[] = $segment;
        }

        return implode('/', $segments);
    }

    /**
     * @param list<string> $paths
     */
    public function inferOperation(array $paths): ?string
    {
        $operations = [];

        foreach ($paths as $path) {
            $filename = basename($path);

            if (preg_match('/^(Create|Store|Index|Show|Edit|Update|Destroy|Delete|Move|Reorder|Deactivate|Reactivate)/i', $filename, $match) === 1) {
                $operations[] = $this->canonicalOperation($match[1]);
            }
        }

        $operations = array_values(array_unique($operations));

        return count($operations) === 1 ? $operations[0] : null;
    }

    public function canonicalOperation(string $operation): string
    {
        $normalized = strtolower(str_replace('_', '-', trim($operation)));

        return $this->catalog->operationAliases()[$normalized] ?? $normalized;
    }

    public function canonicalConcern(string $concern): string
    {
        $normalized = strtolower(str_replace('_', '-', trim($concern)));

        return $this->catalog->concernAliases()[$normalized] ?? $normalized;
    }

    /**
     * @return list<string>
     */
    public function operationReferences(string $setId, string $operation): array
    {
        $set = $this->catalog->operationSets()[$setId] ?? null;

        if (! is_array($set)) {
            throw new ContextException("Unknown operation set: {$setId}");
        }

        return $this->stringList($set[$operation] ?? [], "operation {$operation} in {$setId}");
    }

    /**
     * @param list<array<string, mixed>> $matches
     */
    public function operationApplied(array $matches, string $operation): bool
    {
        foreach ($matches as $match) {
            foreach ($this->catalog->pathRules() as $rule) {
                if (($rule['id'] ?? null) !== $match['rule'] || ! isset($rule['operation_set'])) {
                    continue;
                }

                $set = $this->catalog->operationSets()[(string) $rule['operation_set']] ?? [];

                if (isset($set[$operation])) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param list<array<string, mixed>> $matches
     * @return list<string>
     */
    public function availableOperations(array $matches): array
    {
        $operations = [];

        foreach ($matches as $match) {
            foreach ($this->catalog->pathRules() as $rule) {
                if (($rule['id'] ?? null) !== $match['rule'] || ! isset($rule['operation_set'])) {
                    continue;
                }

                $set = $this->catalog->operationSets()[(string) $rule['operation_set']] ?? [];
                $operations = [...$operations, ...array_keys($set)];
            }
        }

        $operations = array_values(array_unique($operations));
        sort($operations, SORT_STRING);

        return $operations;
    }

    /**
     * @param list<string> $allowedPatterns
     */
    public function ownerAllowed(string $owner, array $allowedPatterns): bool
    {
        foreach ($allowedPatterns as $pattern) {
            $regex = '/^'.str_replace('\\*', '.*', preg_quote($pattern, '/')).'$/';

            if (preg_match($regex, $owner) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    public function stringList(mixed $value, string $label): array
    {
        if (! is_array($value) || ! array_is_list($value)) {
            throw new ContextException(ucfirst($label).' must be a list.');
        }

        foreach ($value as $item) {
            if (! is_string($item) || $item === '') {
                throw new ContextException(ucfirst($label).' must contain non-empty strings.');
            }
        }

        return $value;
    }
}
