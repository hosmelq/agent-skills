# Composer and Nub Build Graph

## When To Use

Read this focused reference when the task involves composer and nub build graph.

## Pattern

### Composer and Nub Build Graph

Project scripts form a dependency graph. Generate route and application types
before the frontend build that imports them.

```json
{
  "scripts": {
    "setup": [
      "@composer install",
      "test -f .env || (cp .env.example .env && \"$PHP_BINARY\" artisan key:generate)",
      "test -f .env.testing || (cp .env.testing.example .env.testing && \"$PHP_BINARY\" artisan key:generate --env=testing)",
      "@php artisan app:download-reference-data-database",
      "@php artisan migrate --force",
      "nub install --frozen-lockfile",
      "nub run build"
    ],
    "agent:setup": [
      "@php artisan boost:install --ansi --guidelines --skills --no-interaction",
      "nubx -y skills experimental_install"
    ],
    "fresh": "@php artisan migrate:fresh --seed",
    "phpstan": "phpstan analyse --memory-limit=4G",
    "pint": "pint",
    "post-update-cmd": [
      "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
      "@composer bump",
      "@composer normalize",
      "@php artisan boost:install --ansi --guidelines --skills --no-interaction"
    ],
    "pre-package-uninstall": ["Illuminate\\Foundation\\ComposerScripts::prePackageUninstall"],
    "post-autoload-dump": [
      "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
      "@php artisan package:discover --ansi",
      "@php artisan filament:upgrade"
    ],
    "post-root-package-install": [
      "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
    ],
    "rector": "rector",
    "test": "@php artisan test",
    "typescript": "@php artisan typescript:transform --no-interaction",
    "wayfinder": "@php artisan wayfinder:generate --skip-routes"
  },
  "scripts-descriptions": {
    "agent:setup": "Runs project agent setup.",
    "fresh": "Recreates and seeds the local database.",
    "phpstan": "Runs static analysis.",
    "pint": "Formats PHP.",
    "rector": "Applies automated PHP refactors.",
    "setup": "Prepares environment, dependencies, data, schema, and frontend assets.",
    "test": "Runs the application tests.",
    "typescript": "Generates TypeScript definitions from PHP types.",
    "wayfinder": "Generates TypeScript definitions for controllers."
  },
  "config": {
    "allow-plugins": {
      "ergebnis/composer-normalize": true,
      "pestphp/pest-plugin": true,
      "php-http/discovery": true,
      "phpstan/extension-installer": true
    }
  }
}
```

The package-manager build invokes `prebuild`, which delegates generated
controller and application types to Composer. Do not create a parallel Nub
command layer when the project has no authored Nub commands.

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
