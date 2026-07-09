# Package Configuration Provider Example

## When To Use

Read this focused reference when the task involves package configuration provider example.

## Pattern

### Package Configuration Provider Example

Package extension providers may extend the package's service provider and override the package configure hook instead of implementing a generic `boot()` method:

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use Package\Transformer\BaseTransformerServiceProvider;
use Package\Transformer\ConfigFactory;

class ExampleTransformerServiceProvider extends BaseTransformerServiceProvider
{
    protected function configure(ConfigFactory $config): void
    {
        $config
            ->transformDirectories(app_path('Enums'))
            ->outputDirectory(resource_path('js/types'))
            ->writer(new ExampleWriter('generated/enums.ts'));
    }
}
```

## Related References

- [`../README.md`](../README.md)
