# Helper Enum Example

## When To Use

Read this focused reference when the task involves helper enum example.

## Pattern

### Helper Enum Example

```php
<?php

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string Alphanumeric()
 * @method static string Letters()
 * @method static string Numbers()
 */
enum ExampleAlphabetType: string
{
    use Comparable;
    use InvokableCases;
    use Values;

    case Alphanumeric = 'alphanumeric';
    case Letters = 'letters';
    case Numbers = 'numbers';

    public function alphabet(): string
    {
        return match($this) {
            self::Alphanumeric => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
            self::Letters => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            self::Numbers => '0123456789',
        };
    }

    public function label(): string
    {
        return match($this) {
            self::Alphanumeric => 'Alphanumeric',
            self::Letters => 'Letters only (A-Z)',
            self::Numbers => 'Numbers only (0-9)',
        };
    }
}
```

## Related References

- [`../README.md`](../README.md)
