<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class MarkdownGraph
{
    private readonly string $skillRoot;

    /** @var array<string, list<string>> */
    private array $links = [];

    /** @var array<string, list<string>> */
    private array $anchors = [];

    /** @var array<string, string> */
    private array $titles = [];

    /** @var list<string> */
    private array $errors = [];

    /** @var list<string> */
    private array $markdownFiles = [];

    public function __construct(string $skillRoot)
    {
        $resolvedRoot = realpath($skillRoot);

        if ($resolvedRoot === false) {
            throw new ContextException("Skill root does not exist: {$skillRoot}");
        }

        $this->skillRoot = $resolvedRoot;
        $this->build();
    }

    /**
     * @return list<string>
     */
    public function markdownFiles(): array
    {
        return $this->markdownFiles;
    }

    /**
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @return list<string>
     */
    public function outgoing(string $relativePath): array
    {
        return $this->links[$relativePath] ?? [];
    }

    public function title(string $relativePath): string
    {
        return $this->titles[$relativePath] ?? $relativePath;
    }

    public function hasAnchor(string $relativePath, string $anchor): bool
    {
        return in_array($anchor, $this->anchors[$relativePath] ?? [], true);
    }

    /**
     * @return list<string>
     */
    public function reachableFrom(string $start): array
    {
        $queue = [$start];
        $reachable = [];

        while ($queue !== []) {
            $current = array_shift($queue);

            if (isset($reachable[$current])) {
                continue;
            }

            $reachable[$current] = true;

            foreach ($this->outgoing($current) as $target) {
                $queue[] = $target;
            }
        }

        return array_keys($reachable);
    }

    private function build(): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->skillRoot, RecursiveDirectoryIterator::SKIP_DOTS),
        );
        $files = [];

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $files[] = $file->getRealPath();
            }
        }

        sort($files, SORT_STRING);

        $sources = [];

        foreach ($files as $absolutePath) {
            $relativePath = $this->relativePath($absolutePath);
            $this->markdownFiles[] = $relativePath;
            $source = (string) file_get_contents($absolutePath);
            $sources[$relativePath] = $source;
            $this->inspectStructure($relativePath, $source);
        }

        foreach ($sources as $relativePath => $source) {
            $this->inspectLinks($relativePath, $source);
        }
    }

    private function inspectStructure(string $relativePath, string $source): void
    {
        $lines = preg_split('/\R/u', $source) ?: [];
        $fenceCount = 0;

        foreach ($lines as $line) {
            if (str_starts_with($line, '```')) {
                $fenceCount++;
            }

            if (preg_match('/^#{1,6}\s+(.+?)\s*#*\s*$/u', $line, $match) === 1) {
                $this->anchors[$relativePath][] = self::anchorFor($match[1]);

                if (! isset($this->titles[$relativePath]) && str_starts_with($line, '# ')) {
                    $this->titles[$relativePath] = trim($match[1]);
                }
            }
        }

        if ($fenceCount % 2 !== 0) {
            $this->errors[] = "Unbalanced fences: {$relativePath}";
        }

    }

    private function inspectLinks(string $relativePath, string $source): void
    {
        preg_match_all('/\[[^\]]*\]\(([^)]+)\)/u', $source, $matches);

        foreach ($matches[1] ?? [] as $rawTarget) {
            $target = $this->cleanTarget((string) $rawTarget);

            if ($target === '' || preg_match('/^(?:https?:|mailto:)/i', $target) === 1) {
                continue;
            }

            [$pathPart, $fragment] = array_pad(explode('#', $target, 2), 2, null);
            $targetPath = $pathPart === ''
                ? $relativePath
                : $this->normalizeRelative(dirname($relativePath).'/'.$pathPart);

            if ($targetPath === null || ! is_file($this->skillRoot.'/'.$targetPath)) {
                $this->errors[] = "Missing link: {$relativePath} -> {$target}";

                continue;
            }

            if (str_ends_with($targetPath, '.md') && ! in_array($targetPath, $this->links[$relativePath] ?? [], true)) {
                $this->links[$relativePath][] = $targetPath;
            }

            if ($fragment !== null && $fragment !== '' && ! $this->hasAnchor($targetPath, $fragment)) {
                $this->errors[] = "Missing anchor: {$relativePath} -> {$target}";
            }
        }
    }

    private function cleanTarget(string $rawTarget): string
    {
        $target = trim($rawTarget);

        if (str_starts_with($target, '<') && str_contains($target, '>')) {
            return substr($target, 1, (int) strpos($target, '>') - 1);
        }

        return preg_split('/\s+(?=["\'])/u', $target, 2)[0] ?? $target;
    }

    private function normalizeRelative(string $path): ?string
    {
        $normalized = [];

        foreach (explode('/', str_replace('\\', '/', $path)) as $segment) {
            if ($segment === '' || $segment === '.') {
                continue;
            }

            if ($segment === '..') {
                if ($normalized === []) {
                    return null;
                }

                array_pop($normalized);

                continue;
            }

            $normalized[] = $segment;
        }

        return implode('/', $normalized);
    }

    private function relativePath(string $absolutePath): string
    {
        return ltrim(substr($absolutePath, strlen($this->skillRoot)), DIRECTORY_SEPARATOR);
    }

    private static function anchorFor(string $heading): string
    {
        $anchor = strtolower($heading);
        $anchor = preg_replace('/[`*_~]/u', '', $anchor) ?? $anchor;
        $anchor = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $anchor) ?? $anchor;
        $anchor = preg_replace('/\s+/u', '-', trim($anchor)) ?? $anchor;

        return preg_replace('/-+/u', '-', $anchor) ?? $anchor;
    }
}
