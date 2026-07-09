# Current Workspace State

## When To Use

Use this leaf for persisted current-Workspace transitions.

## Pattern

### Current Workspace State

Use this path when a model method persists or rejects current-`Workspace` state.

```php
it('sets current_workspace_id when loading currentWorkspace if it is not already set', function (): void {
    $actor = Actor::factory()
        ->has(Workspace::factory(), 'workspaces')
        ->createOne();
    $workspace = $actor->workspaces()->sole();

    expect($actor->current_workspace_id)->toBeNull();

    $actor->currentWorkspace;

    assertDatabaseHas(Actor::class, [
        'id' => $actor->id,
        'current_workspace_id' => $workspace->id,
    ]);
});

it('cannot switch to an unrelated Workspace', function (): void {
    $unrelatedWorkspace = Workspace::factory()->createOne();
    $actor = Actor::factory()->createOne();

    expect($actor)
        ->switchWorkspace($unrelatedWorkspace)->toBeFalse()
        ->currentWorkspace->toBeNull();
});

it('can switch to a related Workspace', function (): void {
    $workspace = Workspace::factory()->createOne();
    $actor = Actor::factory()->withWorkspace($workspace)->createOne();

    expect($actor)
        ->switchWorkspace($workspace)->toBeTrue()
        ->currentWorkspace->is($workspace)->toBeTrue();
});
```

## Related References

- [Parent router](../README.md)
