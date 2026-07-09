<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

use JsonException;

final class Catalog
{
    /**
     * @param array<string, mixed> $data
     */
    private function __construct(
        private readonly string $skillRoot,
        private readonly array $data,
    ) {}

    public static function load(string $skillRoot): self
    {
        $resolvedRoot = realpath($skillRoot);

        if ($resolvedRoot === false) {
            throw new ContextException("Skill root does not exist: {$skillRoot}");
        }

        $catalogPath = $resolvedRoot.'/catalog.json';

        if (! is_file($catalogPath)) {
            throw new ContextException("Catalog does not exist: {$catalogPath}");
        }

        $data = self::decodeJsonFile($catalogPath);

        if (! is_array($data)) {
            throw new ContextException('Catalog root must be a JSON object.');
        }

        $includes = $data['includes'] ?? [];

        if (! is_array($includes) || ! array_is_list($includes)) {
            throw new ContextException('Catalog includes must be a list.');
        }

        unset($data['includes']);

        foreach ($includes as $include) {
            if (! is_string($include) || $include === '' || str_starts_with($include, '/') || in_array('..', explode('/', str_replace('\\', '/', $include)), true)) {
                throw new ContextException('Catalog include paths must be safe relative paths.');
            }

            $includePath = realpath($resolvedRoot.'/'.$include);

            if ($includePath === false || ! is_file($includePath) || ! str_starts_with($includePath, $resolvedRoot.DIRECTORY_SEPARATOR)) {
                throw new ContextException("Catalog include does not exist inside the skill: {$include}");
            }

            $fragment = self::decodeJsonFile($includePath);

            if (! is_array($fragment)) {
                throw new ContextException("Catalog include must contain an object: {$include}");
            }

            $data = self::mergeFragment($data, $fragment, $include);
        }

        return new self($resolvedRoot, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(string $skillRoot, array $data): self
    {
        $resolvedRoot = realpath($skillRoot);

        if ($resolvedRoot === false) {
            throw new ContextException("Skill root does not exist: {$skillRoot}");
        }

        return new self($resolvedRoot, $data);
    }

    public function skillRoot(): string
    {
        return $this->skillRoot;
    }

    /**
     * @return array<string, mixed>
     */
    public function data(): array
    {
        return $this->data;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function pathRules(): array
    {
        $rules = $this->data['path_rules'] ?? null;

        if (! is_array($rules) || ! array_is_list($rules)) {
            throw new ContextException('Catalog path_rules must be a list.');
        }

        return $rules;
    }

    /**
     * @return array<string, array<string, list<string>>>
     */
    public function operationSets(): array
    {
        $sets = $this->data['operation_sets'] ?? [];

        if (! is_array($sets)) {
            throw new ContextException('Catalog operation_sets must be an object.');
        }

        return $sets;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function concerns(): array
    {
        $concerns = $this->data['concerns'] ?? [];

        if (! is_array($concerns)) {
            throw new ContextException('Catalog concerns must be an object.');
        }

        return $concerns;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function gates(): array
    {
        $gates = $this->data['gates'] ?? [];

        if (! is_array($gates)) {
            throw new ContextException('Catalog gates must be an object.');
        }

        return $gates;
    }

    /**
     * @return array<string, string>
     */
    public function operationAliases(): array
    {
        $aliases = $this->data['operation_aliases'] ?? [];

        if (! is_array($aliases)) {
            throw new ContextException('Catalog operation_aliases must be an object.');
        }

        return $aliases;
    }

    /**
     * @return array<string, string>
     */
    public function concernAliases(): array
    {
        $aliases = $this->data['concern_aliases'] ?? [];

        if (! is_array($aliases)) {
            throw new ContextException('Catalog concern_aliases must be an object.');
        }

        return $aliases;
    }

    public function defaultInt(string $key): int
    {
        $value = $this->data['defaults'][$key] ?? null;

        if (! is_int($value) || $value < 1) {
            throw new ContextException("Catalog default {$key} must be a positive integer.");
        }

        return $value;
    }

    /**
     * @return list<string>
     */
    public function globalGates(): array
    {
        $gates = $this->data['global_gates'] ?? [];

        if (! is_array($gates) || ! array_is_list($gates)) {
            throw new ContextException('Catalog global_gates must be a list.');
        }

        return $gates;
    }

    /**
     * @return list<string>
     */
    public function navigationExclusions(): array
    {
        $references = $this->data['navigation_exclusions'] ?? [];

        if (! is_array($references) || ! array_is_list($references)) {
            throw new ContextException('Catalog navigation_exclusions must be a list.');
        }

        return $references;
    }

    public function absoluteReference(string $relativePath): string
    {
        if ($relativePath === '' || str_starts_with($relativePath, '/') || str_contains($relativePath, "\0")) {
            throw new ContextException("Unsafe reference path: {$relativePath}");
        }

        $segments = explode('/', str_replace('\\', '/', $relativePath));

        if (in_array('..', $segments, true)) {
            throw new ContextException("Reference escapes the skill root: {$relativePath}");
        }

        $absolutePath = realpath($this->skillRoot.'/'.$relativePath);

        if ($absolutePath === false || ! is_file($absolutePath)) {
            throw new ContextException("Missing reference: {$relativePath}");
        }

        if (! str_starts_with($absolutePath, $this->skillRoot.DIRECTORY_SEPARATOR)) {
            throw new ContextException("Reference escapes the skill root: {$relativePath}");
        }

        return $absolutePath;
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeJsonFile(string $path): array
    {
        try {
            $data = json_decode(
                (string) file_get_contents($path),
                true,
                flags: JSON_THROW_ON_ERROR,
            );
        } catch (JsonException $exception) {
            throw new ContextException("Invalid catalog JSON in {$path}: {$exception->getMessage()}");
        }

        if (! is_array($data) || array_is_list($data)) {
            throw new ContextException("Catalog JSON must contain an object: {$path}");
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $catalog
     * @param array<string, mixed> $fragment
     * @return array<string, mixed>
     */
    private static function mergeFragment(array $catalog, array $fragment, string $source): array
    {
        foreach ($fragment as $key => $value) {
            if (! array_key_exists($key, $catalog)) {
                $catalog[$key] = $value;

                continue;
            }

            if (! is_array($catalog[$key]) || ! is_array($value)) {
                throw new ContextException("Catalog include {$source} duplicates scalar key {$key}.");
            }

            if (array_is_list($catalog[$key]) && array_is_list($value)) {
                $catalog[$key] = [...$catalog[$key], ...$value];

                continue;
            }

            if (array_is_list($catalog[$key]) !== array_is_list($value)) {
                throw new ContextException("Catalog include {$source} changes the shape of {$key}.");
            }

            $duplicates = array_intersect(array_keys($catalog[$key]), array_keys($value));

            if ($duplicates !== []) {
                throw new ContextException("Catalog include {$source} duplicates {$key} entries: ".implode(', ', $duplicates));
            }

            $catalog[$key] = [...$catalog[$key], ...$value];
        }

        return $catalog;
    }
}
