# Deferrable Binding Provider Example

## When To Use

Read this focused reference when the task involves deferrable binding provider example.

## Pattern

### Deferrable Binding Provider Example

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Override;

class ExampleIdServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return list<string>
     */
    #[Override]
    public function provides(): array
    {
        return [ExampleIdClient::class, 'example-id'];
    }

    #[Override]
    public function register(): void
    {
        $this->app->singleton(ExampleIdClient::class);

        $this->app->alias(ExampleIdClient::class, 'example-id');
    }
}
```

## Related References

- [`../README.md`](../README.md)
