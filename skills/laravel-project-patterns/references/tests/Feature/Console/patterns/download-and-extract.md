# Download And Extract Command Tests

## When To Use

Use this leaf for HTTP download, archive extraction, and filesystem command tests.

## Pattern

### Download/Extract Command Pattern

For commands that download files:

- point output to a test storage path;
- fake the exact configured URL;
- serve bytes from `tests/testfiles` when a fixture exists;
- keep success, existing-file skip, forced replacement, and malformed forced
  replacement in that order;
- assert the extracted/written file or database integrity;
- prove a malformed forced refresh preserves the existing destination;
- clean up only the file the test created.

```php
<?php

declare(strict_types=1);

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

it('downloads and extracts the reference data database', function (): void {
    $databasePath = storage_path('framework/testing/reference-data.sqlite3');

    Config::set('database.connections.reference_data.database', $databasePath);

    Http::fake([
        Config::string('reference-data-database.download_url') => Http::response(
            File::get(base_path('tests/testfiles/reference-data.sqlite3.gz'))
        ),
    ]);

    artisan('app:download-reference-data-database')->assertSuccessful();

    $database = new PDO('sqlite:'.$databasePath);

    expect($database->query('PRAGMA integrity_check')->fetchColumn())->toBe('ok');

    File::delete($databasePath);
});

it('skips downloading when the reference data database already exists', function (): void {
    $databasePath = storage_path('framework/testing/existing-reference-data.sqlite3');

    Config::set('database.connections.reference_data.database', $databasePath);

    File::put($databasePath, 'existing reference data database');

    artisan('app:download-reference-data-database')
        ->expectsOutputToContain(
            'Reference data SQLite database already exists.',
        )
        ->assertSuccessful();

    expect(File::get($databasePath))
        ->toBe('existing reference data database');

    File::delete($databasePath);
});

it('downloads and replaces the reference data database when forced', function (): void {
    $databasePath = storage_path('framework/testing/forced-reference-data.sqlite3');

    Config::set('database.connections.reference_data.database', $databasePath);

    File::put($databasePath, 'existing reference data database');

    Http::fake([
        Config::string('reference-data-database.download_url') => Http::response(
            File::get(base_path('tests/testfiles/reference-data.sqlite3.gz'))
        ),
    ]);

    artisan('app:download-reference-data-database', [
        '--force' => true,
    ])->assertSuccessful();

    expect(File::get($databasePath))
        ->not->toBe('existing reference data database');

    File::delete($databasePath);
});

it('preserves the existing reference data database when a forced download is malformed', function (): void {
    $databasePath = storage_path('framework/testing/invalid-reference-data.sqlite3');

    Config::set('database.connections.reference_data.database', $databasePath);

    File::put($databasePath, 'existing reference data database');

    Http::fake([
        Config::string('reference-data-database.download_url') => Http::response(
            File::get(base_path('tests/testfiles/malformed-reference-data.sqlite3.gz'))
        ),
    ]);

    expect(fn () => artisan('app:download-reference-data-database', [
        '--force' => true,
    ]))
        ->toThrow(PDOException::class);

    expect(File::get($databasePath))
        ->toBe('existing reference data database');

    File::delete($databasePath);
});
```

## Related References

- [Parent router](../README.md)
