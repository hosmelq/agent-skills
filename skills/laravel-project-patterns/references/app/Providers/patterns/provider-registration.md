# Provider Registration

## When To Use

Read this focused reference when the task involves provider registration.

## Pattern

### Provider Registration

Register application providers explicitly in `bootstrap/providers.php`.

```php
<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\ExampleIdServiceProvider;

return [
    AppServiceProvider::class,
    ExampleIdServiceProvider::class,
];
```

## Related References

- [`../README.md`](../README.md)
