# Create And Child Mutations

## When To Use

Read this focused reference when the task involves create and child mutations.

## Pattern

### Create And Child Mutations

Create actions accept the parent or `Workspace` model when that model is the direct business input for creating the new row:

```php
public function handle(ParentRecord $parentRecord, CreateChildRecordInput $input): ChildRecord
{
    return $parentRecord->children()->create($input->transform());
}
```

For update, delete, and lifecycle mutations, accept the child model directly. The entrypoint owns route hierarchy, ownership, authorization, and soft-delete binding. This keeps actions usable from commands, jobs, tests, and Tinker without reconstructing the HTTP route hierarchy.

If the action needs a parent or owner for a business rule, derive that relationship from the child. Do not make the caller pass it merely to re-query the same child. Query fresh state only for an action-owned transactional guard, lock, or required relationship read.

```php
public function handle(
    ChildRecord $childRecord,
    UpdateChildRecordInput $input,
): ChildRecord {
    return tap($childRecord)->update($input->transform());
}
```

When creating a leaf record under a child, the child remains a direct business input. Derive any parent needed by a business guard, and keep user-facing range checks in private helpers. Back cross-row uniqueness and non-overlap rules with database constraints:

```php
public function handle(ChildRecord $childRecord, CreateLeafRecordInput $input): LeafRecord
{
    return DB::transaction(function () use ($childRecord, $input): LeafRecord {
        $parentRecord = $childRecord->parentRecord()->firstOrFail();

        if ($parentRecord->deactivated_at !== null) {
            throw CannotCreateLeafRecord::becauseParentIsDeactivated();
        }

        $this->ensureRangeIsAvailable($childRecord, $input);

        return $childRecord->leaves()->create($input->transform());
    });
}
```

Do not move transactional business guards into broad model helpers just to reduce a repeated query. Keep application behavior in the action unless a local reusable abstraction already exists for that exact domain.

## Related References

- [`../README.md`](../README.md)
