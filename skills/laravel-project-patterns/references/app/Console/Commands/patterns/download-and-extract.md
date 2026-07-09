# Download And Extract Command

## When To Use

Use for a command that downloads, validates, and replaces a local artifact.

## Pattern

- Use `Http::retry(...)->sink(...)->get(...)->throw()` and a
  `TemporaryDirectory` for temporary downloads.
- Skip an existing destination unless the operator passes `--force`.
- Download and decompress beside the destination, validate the temporary
  database, then rename atomically. A failed forced refresh preserves the
  previous destination.
- Use `Safe\*` filesystem transforms when the command depends on
  exception-on-failure behavior.

```php
<?php

declare(strict_types=1);

namespace App\Console\Commands;

use function Safe\copy;
use function Safe\rename;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PDO;
use RuntimeException;
use Spatie\TemporaryDirectory\TemporaryDirectory;

#[Description('Download the reference data SQLite database.')]
#[Signature('app:download-reference-data-database {--force : Replace an existing database}')]
class DownloadReferenceDataDatabaseCommand extends Command
{
    public function handle(): int
    {
        $databasePath = Config::string(
            'database.connections.reference_data.database',
        );

        if (File::exists($databasePath) && ! $this->option('force')) {
            $this->components->info(
                'Reference data SQLite database already exists.',
            );

            return Command::SUCCESS;
        }

        $temporaryDirectory = new TemporaryDirectory(dirname($databasePath))
            ->deleteWhenDestroyed()
            ->create();
        $temporaryDatabasePath = $temporaryDirectory->path(
            'reference-data.sqlite3',
        );

        Http::retry(3, 500)
            ->sink($temporaryDirectory->path('reference-data.sqlite3.gz'))
            ->get(Config::string('reference-data-database.download_url'))
            ->throw();

        copy(
            sprintf('compress.zlib://%s', $temporaryDirectory->path('reference-data.sqlite3.gz')),
            $temporaryDatabasePath,
        );

        $pdo = new PDO('sqlite:'.$temporaryDatabasePath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $integrityCheck = $pdo->query('PRAGMA integrity_check');

        throw_if(
            $integrityCheck === false || $integrityCheck->fetchColumn() !== 'ok',
            RuntimeException::class,
            'Reference data SQLite database failed integrity check.'
        );

        rename($temporaryDatabasePath, $databasePath);

        $this->components->info('Reference data SQLite database downloaded successfully.');

        return Command::SUCCESS;
    }
}
```

## Related References

- [Parent router](../README.md)
