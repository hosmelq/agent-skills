<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\ContextException;

final class TestHarness
{
    private int $tests = 0;

    /** @var list<string> */
    private array $failures = [];

    /** @var array<string, int> */
    private array $metrics = [];

    public function __construct(private readonly string $contextScript) {}

    public function check(bool $condition, string $message): void
    {
        $this->tests++;

        if (! $condition) {
            $this->failures[] = $message;
        }
    }

    public function same(mixed $expected, mixed $actual, string $message): void
    {
        $this->check($expected === $actual, $message);
    }

    public function expectContextException(callable $callback, string $contains, int $exitCode): void
    {
        try {
            $callback();
            $this->check(false, "Expected ContextException containing {$contains}.");
        } catch (ContextException $exception) {
            $this->check(
                str_contains($exception->getMessage(), $contains),
                "Unexpected exception message: {$exception->getMessage()}",
            );
            $this->same($exitCode, $exception->exitCode, "Expected exit code {$exitCode}, got {$exception->exitCode}.");
        }
    }

    /**
     * @param list<string> $arguments
     * @return array{output: string, exit_code: int}
     */
    public function runCli(array $arguments): array
    {
        $command = escapeshellarg(PHP_BINARY).' '.escapeshellarg($this->contextScript);

        foreach ($arguments as $argument) {
            $command .= ' '.escapeshellarg($argument);
        }

        $lines = [];
        $exitCode = 0;
        exec($command.' 2>&1', $lines, $exitCode);

        return [
            'output' => implode(PHP_EOL, $lines),
            'exit_code' => $exitCode,
        ];
    }

    public function metric(string $name, int $value): void
    {
        $this->metrics[$name] = $value;
    }

    public function finish(): never
    {
        ksort($this->metrics, SORT_STRING);
        fwrite(STDOUT, 'tests='.$this->tests.PHP_EOL);
        fwrite(STDOUT, 'failures='.count($this->failures).PHP_EOL);

        foreach ($this->metrics as $name => $value) {
            fwrite(STDOUT, $name.'='.$value.PHP_EOL);
        }

        foreach ($this->failures as $failure) {
            fwrite(STDOUT, $failure.PHP_EOL);
        }

        exit($this->failures === [] ? 0 : 1);
    }
}
