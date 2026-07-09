# Simple Values Enum Example

## When To Use

Read this focused reference when the task involves simple values enum example.

## Pattern

### Simple Values Enum Example

```php
<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Values;

enum ExampleVariant: string
{
    use Values;

    case Accent = 'accent';
    case Default = 'default';
    case Success = 'success';
}
```

## Related References

- [`../README.md`](../README.md)
