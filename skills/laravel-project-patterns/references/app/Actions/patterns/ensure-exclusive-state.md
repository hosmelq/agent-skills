# Ensure and Select an Exclusive State

## When To Use

Use when one eligible record per owner may hold a distinguished state and the
application needs both an idempotent ensure operation and an explicit select
operation.

## Pattern

Keep the business branches distinct:

1. return the existing active eligible selection;
2. otherwise promote the first active eligible candidate in domain order;
3. otherwise create a synthetic default with server-owned values.

```php
public function handle(Workspace $workspace): ParentRecord
{
    return DB::transaction(function () use ($workspace): ParentRecord {
        $selected = $workspace->parentRecords()
            ->where('is_selected', true)
            ->where('category', RecordCategory::Eligible)
            ->active()
            ->first();

        if ($selected instanceof ParentRecord) {
            return $selected;
        }

        $candidate = $workspace->parentRecords()
            ->where('category', RecordCategory::Eligible)
            ->active()
            ->ordered()
            ->first();

        if ($candidate instanceof ParentRecord) {
            return $this->selectParentRecord->handle($candidate);
        }

        return $workspace->parentRecords()->create([
            'category' => RecordCategory::Eligible,
            'is_selected' => true,
            'name' => __('records.defaults.selected_name'),
        ]);
    });
}
```

The explicit selector validates eligibility, clears stale flags inside the
same owner scope—including soft-deleted history only when the invariant
requires it—and then selects the target:

```php
public function handle(ParentRecord $parentRecord): ParentRecord
{
    if (! $parentRecord->canBeSelected()) {
        throw CannotSelectParentRecord::becauseItIsNotActiveAndEligible();
    }

    ParentRecord::query()
        ->withTrashed()
        ->where('workspace_id', $parentRecord->workspace_id)
        ->where('is_selected', true)
        ->update(['is_selected' => false]);

    $parentRecord->update(['is_selected' => true]);

    return $parentRecord->refresh();
}
```

This example describes branch and scope behavior, not a default concurrency
strategy. Prefer a database constraint when it can enforce exclusivity. Load
the shared-root protocol reference only when the live system already satisfies
its complete applicability gate.

## Related References

- [`../README.md`](../README.md)
- [`exclusive-default-selection.md`](exclusive-default-selection.md)
- [`existing-shared-root-lock-protocols-only.md`](existing-shared-root-lock-protocols-only.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../tests/Integration/Actions/scenario-catalog/state-and-order-actions.md)
