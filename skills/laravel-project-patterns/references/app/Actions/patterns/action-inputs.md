# Action Inputs

## When To Use

Read this focused reference when the task involves action inputs.

## Pattern

### Action Inputs

For actions that mutate an already-bound model, do not accept route parents or owners only to prove ownership again. The entrypoint's scoped binding and policy own that boundary. Mutate the bound model directly unless the action owns another reason to query fresh database state:

```php
public function handle(ParentRecord $parentRecord, UpdateParentRecordInput $input): ParentRecord
{
    $parentRecord->update($input->transform());

    return $parentRecord;
}
```

Lifecycle and delete actions should remain direct when they perform one mutation. Keep a transaction when the action coordinates multiple writes or an action-owned guard with the mutation:

```php
public function handle(ParentRecord $parentRecord): void
{
    DB::transaction(function () use ($parentRecord): void {
        if ($parentRecord->children()->withTrashed()->exists()) {
            throw CannotDeleteParentRecord::becauseItHasDependencies();
        }

        $parentRecord->delete();
    });
}
```

## Related References

- [`../README.md`](../README.md)
