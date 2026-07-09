# Seeders

## When To Use

Read this focused reference when the task involves seeders.

## Pattern

### Seeders

Use seeders for local/demo data only unless a task explicitly requires
production seed data. Keep ownership graphs coherent by creating the top-level
`Workspace`, attaching actors, establishing application invariants through the
same action used by the app, and then creating dependent records.

```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\EnsureWorkspaceDefaults;
use App\Models\Actor;
use App\Models\ExampleRecord;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class LocalSeeder extends Seeder
{
    public function run(EnsureWorkspaceDefaults $ensureWorkspaceDefaults): void
    {
        $actor = Actor::factory()->createOne([
            'email' => 'actor@example.com',
        ]);

        $workspaces = Workspace::factory()
            ->count(2)
            ->for($actor, 'owner')
            ->create();

        $actor->workspaces()->sync($workspaces);

        $workspaces->each(function (Workspace $workspace) use ($ensureWorkspaceDefaults): void {
            $ensureWorkspaceDefaults->handle($workspace);

            ExampleRecord::factory()
                ->for($workspace)
                ->count(3)
                ->create();
        });
    }
}
```

Inject an action into `run(...)` only when seeded dependent data relies on an
application invariant that the action owns. Call it before creating those
dependents. Do not copy the invariant into the seeder, and do not use seeders
as a substitute for factory states or tests.

## Related References

- [`../README.md`](../README.md)
