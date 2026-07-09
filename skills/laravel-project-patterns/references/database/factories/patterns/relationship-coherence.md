# Relationship Coherence

## When To Use

Read this focused reference when the task involves relationship coherence.

## Pattern

### Relationship Coherence

When a child model also stores denormalized ownership, derive it from the parent to avoid inconsistent test data:

```php
'workspace_id' => fn (array $attributes): int => ParentRecord::query()
    ->findOrFail($attributes['parent_record_id'])
    ->workspace_id,
```

When two stored parents must share the same owner, derive the related factory
from the already selected direct parent:

```php
'parent_record_id' => ParentRecord::factory(),
'related_record_id' => fn (array $attributes): Factory => RelatedRecord::factory()
    ->state(fn (): array => [
        'workspace_id' => ParentRecord::query()
            ->findOrFail($attributes['parent_record_id'])
            ->workspace_id,
    ]),

'workspace_id' => fn (array $attributes): int => ParentRecord::query()
    ->findOrFail($attributes['parent_record_id'])
    ->workspace_id,
```

Default factories must create coherent ownership graphs. Only create mismatched parent/`Workspace` graphs in tests that explicitly assert route-binding, listing, authorization, or validation behavior for invalid ownership. For denormalized ownership cases, derive redundant `Workspace` values from the direct parent by default, then intentionally override them only in boundary tests.

When an optional `belongsTo` model must share ownership, use an `afterCreating` hook with `associate(...)`. The example below is shape-only; `relatedRecord()` represents a `belongsTo` relation:

```php
public function withRelatedRecord(): static
{
    return $this->afterCreating(function (ChildRecord $childRecord): void {
        $childRecord->relatedRecord()->associate(
            RelatedRecord::factory()->createOne([
                'workspace_id' => $childRecord->workspace_id,
            ]),
        );

        $childRecord->save();
    });
}
```

When a factory needs to create or sync a pivot after the model exists, do it in `afterCreating` and keep the caller-controlled values explicit:

```php
/**
 * @param array<string, mixed> $values
 */
public function withWorkspace(null|Workspace $workspace = null, array $values = []): static
{
    $workspace ??= Workspace::factory()->createOne();

    return $this->state(['current_workspace_id' => $workspace->id])
        ->afterCreating(function (Actor $actor) use ($workspace, $values): void {
            $actor->workspaces()->syncWithPivotValues($workspace, $values);
        });
}
```

Keep distinct domain states discoverable on the owning factory:

```php
public function default(): static
{
    return $this->state(['is_default' => true]);
}

public function expired(): static
{
    return $this->state(['expires_at' => now()->subSecond()]);
}

public function used(): static
{
    return $this->state(['used_at' => now()]);
}

public function bounded(): static
{
    return $this->state([
        'minimum_amount' => '10.00',
        'maximum_amount' => '20.00',
    ]);
}

public function withChild(): static
{
    return $this->afterCreating(function (ParentRecord $parentRecord): void {
        ChildRecord::factory()->for($parentRecord)->createOne();
    });
}
```

Lifecycle (`deactivated()`), exclusive default, expired, used, fixed-region,
bounded/open-ended range, rounding, child creation, optional association, and
pivot-sync states are distinct variants. Add only the states meaningful to the
model; do not copy the whole catalog into every factory.

When testing these states, do not infer an assertion from the use of a factory
or `afterCreating()`. Select the applicable fixture, persisted-state, or
reloaded-Eloquent example from
[`persistence-assertions.md`](../../../tests/maps/persistence-assertions.md).

## Related References

- [`../README.md`](../README.md)
