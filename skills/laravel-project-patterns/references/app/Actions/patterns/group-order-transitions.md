# Group Order Transitions

## When To Use

Use when records are ordered inside a category and changing category must
normalize the old group and append the record to the new group.

## Pattern

Direct movement within a group uses a `Move...` action, delegates to the
ordering library, and keeps the nullable move-after branch explicit:

```php
public function handle(
    ParentRecord $parentRecord,
    null|ParentRecord $moveAfterParentRecord,
): void {
    if ($moveAfterParentRecord instanceof ParentRecord) {
        $parentRecord->moveAfter($moveAfterParentRecord);

        return;
    }

    $parentRecord->moveToStart();
}
```

When an update changes the grouping field, reset the target's old order before
the update, normalize the source group, and let the ordering library assign the
highest destination order. If the destination is empty, the resulting order is
one.

```php
$previousCategory = $parentRecord->category;
$categoryChanged = $input->category instanceof RecordCategory
    && $input->category !== $previousCategory;

$attributes = $input->transform();

if ($categoryChanged) {
    $attributes['sort_order'] = 0;
}

$parentRecord->update($attributes);

if (! $categoryChanged) {
    return $parentRecord;
}

$this->normalizeGroupOrder(
    $parentRecord->workspace_id,
    $previousCategory,
);

$parentRecord->setHighestOrderNumber();
$parentRecord->save();

return $parentRecord->refresh();
```

Normalize only the source group and keep the ordering query owner-, group-, and
soft-delete-scoped:

```php
private function normalizeGroupOrder(
    int $workspaceId,
    RecordCategory $category,
): void {
    $orderedIds = ParentRecord::query()
        ->where('workspace_id', $workspaceId)
        ->where('category', $category)
        ->ordered()
        ->pluck('id');

    ParentRecord::setNewOrder(
        $orderedIds,
        modifyQuery: fn (Builder $query): Builder => $query
            ->where('workspace_id', $workspaceId)
            ->where('category', $category)
            ->whereNull('deleted_at'),
    );
}
```

Scope every move/order query by the direct owner and group. A transaction may
be needed for atomic multi-step order changes, but move/order behavior alone
does not justify `lockForUpdate()`.

## Related References

- [`../README.md`](../README.md)
- [`coordinated-writes.md`](coordinated-writes.md)
- [`existing-shared-root-lock-protocols-only.md`](existing-shared-root-lock-protocols-only.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../tests/Integration/Actions/scenario-catalog/state-and-order-actions.md)
