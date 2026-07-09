# Strict Configuration

## When To Use

Read this focused reference when the task involves strict configuration.

## Pattern

### Strict Configuration

Configuration files are strict PHP arrays. Cast scalar environment values and
filter lists so callers receive the declared runtime type.

```php
<?php

declare(strict_types=1);

use App\Support\Media\ExampleFileNamer;

$adminEmails = array_values(array_filter(
    explode(',', (string) env('ADMIN_EMAILS', '')),
));

return [
    'admin_emails' => $adminEmails,
    'history' => [
        'encrypt' => (bool) env('INERTIA_ENCRYPT_HISTORY', true),
    ],
    'media_file_namer' => ExampleFileNamer::class,
    'observability' => [
        'organization_id' => env('OBSERVABILITY_ORGANIZATION_ID') === null
            ? null
            : (int) env('OBSERVABILITY_ORGANIZATION_ID'),
        'sample_rate' => (float) env('OBSERVABILITY_SAMPLE_RATE', 0.5),
    ],
    'reference_database' => [
        'database' => env('DB_DATABASE_REFERENCE', database_path('reference.sqlite3')),
        'driver' => 'sqlite',
    ],
];
```

Represent the important configuration variants explicitly:

| Variant | Required shape |
| --- | --- |
| scalar flags/limits | cast at config load time |
| comma-separated lists | trim, filter empty entries, reindex |
| strategy or transformer | `class-string` value |
| secondary database | named connection with its own path/driver |
| public storage | disk URL derived from the public app URL |
| Inertia history | encrypted history enabled by project config |
| Octane | worker/server settings, never request state in singletons |
| media/health/observability | project-selected strategy and explicit enablement |

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
