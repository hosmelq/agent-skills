# Localization Files

## When To Use

Read this focused reference when the task involves localization files.

## Pattern

### Localization Files

Localization files are strict PHP arrays. Use enum values as translation keys when the enum owns a public label map, and use action keys for toast or validation messages consumed by controllers, resources, requests, actions, or exceptions.

```php
<?php

declare(strict_types=1);

use App\Enums\ExampleStatus;

return [
    'created' => [
        'title' => 'Record created',
    ],

    'updated' => [
        'title' => 'Record updated',
    ],

    'validation' => [
        'locked' => 'This record cannot be changed right now.',
    ],

    'statuses' => [
        ExampleStatus::Active->value => 'Active',
        ExampleStatus::Inactive->value => 'Inactive',
    ],
];
```

When adding a translated enum label, keep the enum helper and unit tests aligned with the translation key shape.

## Related References

- [`../README.md`](../README.md)
