# File Shape

## When To Use

Read this focused reference when the task involves file shape.

## Pattern

### File Shape

Use this structure unless sibling migrations prove otherwise:

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('child_records', function (Blueprint $table): void {
            $table->id();
            $table->caseInsensitiveText('public_id')->unique();

            $table->foreignId('parent_record_id')->index();

            $table->timestamp('deactivated_at')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement(<<<'SQL'
            ALTER TABLE child_records
            ADD CONSTRAINT child_records_public_id_format_check
            CHECK (public_id ~* '^[a-z0-9]{10}$')
        SQL);
    }
};
```

Extension-only and add-column migrations keep the same strict anonymous shape
without pretending to be create-table migrations:

```php
return new class () extends Migration {
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS btree_gist');
    }
};
```

```php
return new class () extends Migration {
    public function up(): void
    {
        Schema::table('actors', function (Blueprint $table): void {
            $table->string('secondary_identity_id')->nullable()->index();
        });
    }
};
```

## Related References

- [`../README.md`](../README.md)
