# Dependency Analysis

## When To Use

Read this focused reference when changing Composer dependency-analysis
configuration.

## Pattern

### Dependency Analysis

Scan non-autoloaded runtime paths explicitly and make every ignored diagnostic
name its extension, package, and error type:

```php
<?php

declare(strict_types=1);

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

return new Configuration()
    ->addPathToScan(__DIR__.'/database/migrations', isDev: false)
    ->ignoreErrors([ErrorType::SHADOW_DEPENDENCY])
    ->ignoreErrorsOnExtension('ext-zlib', [ErrorType::UNUSED_DEPENDENCY])
    ->ignoreErrorsOnPackages([
        'laravel/octane',
        'laravel/wayfinder',
        'league/flysystem-aws-s3-v3',
        'resend/resend-php',
    ], [ErrorType::UNUSED_DEPENDENCY]);
```

These are evidence-backed analyzer exceptions, not general dependency
allowlists. Remove an exception when the package becomes directly visible to
the analyzer or leaves the project.

## Related References

- [`../tooling-php.md`](../tooling-php.md)
