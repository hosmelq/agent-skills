# Coordinated Writes

## When To Use

Read this focused reference when the task involves coordinated writes.

## Pattern

### Coordinated Writes

One transaction may coordinate a root create, owner membership, and current
context because those writes form one domain operation:

```php
public function handle(Actor $actor, CreateWorkspaceInput $input): Workspace
{
    return DB::transaction(function () use ($actor, $input): Workspace {
        $workspace = $actor->ownedWorkspaces()->create($input->transform());

        $actor->workspaces()->attach($workspace, [
            'role' => WorkspaceRole::Owner,
        ]);

        $actor->switchWorkspace($workspace);

        return $workspace;
    });
}
```

A cascading operational delete is also distinct from a guarded direct delete:

```php
public function handle(ParentRecord $parentRecord): void
{
    DB::transaction(function () use ($parentRecord): void {
        $parentRecord->children()->eachById(
            function (ChildRecord $childRecord): void {
                $childRecord->operations()->delete();
            },
        );

        $parentRecord->children()->delete();
        $parentRecord->delete();
    });
}
```

Use this only when cascade behavior is application-owned. Do not reproduce a
database cascade in PHP, and do not turn a permanent-dependency guard into a
cascade.

## Related References

- [`../README.md`](../README.md)
