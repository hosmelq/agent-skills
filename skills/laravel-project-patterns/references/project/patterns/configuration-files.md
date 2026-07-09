# Configuration Files

## When To Use

Read this focused reference when the task involves configuration files.

## Pattern

### Configuration Files

Configuration files return arrays and may import framework/package classes when the config value is a class-string contract. Cast environment values at the config boundary when the consuming package expects a scalar type.

```php
<?php

declare(strict_types=1);

use App\Support\Files\ExampleFileNamer;
use App\Support\Files\ExamplePathGenerator;
use Illuminate\Support\Str;

return [
    'default' => env('EXAMPLE_DISK', 'local'),

    'prefix' => env('EXAMPLE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-example-'),

    'enabled' => (bool) env('EXAMPLE_ENABLED', true),

    'retry_after' => (int) env('EXAMPLE_RETRY_AFTER', 210),

    'sample_rate' => env('EXAMPLE_SAMPLE_RATE') === null ? 1.0 : (float) env('EXAMPLE_SAMPLE_RATE'),

    'file_namer' => ExampleFileNamer::class,
    'path_generator' => ExamplePathGenerator::class,

    'emails' => [
        ...array_filter(
            explode(',', (string) env('EXAMPLE_EMAILS', '')),
            static fn (string $email): bool => $email !== '',
        ),
    ],
];
```

Prefer local helper functions already used by the config file, such as safe URL parsing or multibyte string trimming, instead of ad hoc parsing. Keep package defaults recognizable unless the application intentionally overrides them.

## Related References

- [`../README.md`](../README.md)
