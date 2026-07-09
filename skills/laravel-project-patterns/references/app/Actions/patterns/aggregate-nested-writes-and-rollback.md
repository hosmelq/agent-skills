# Aggregate Nested Writes And Rollback

## When To Use

Read this leaf when one action owns creation or mutation of a root aggregate
and its submitted child rows as one atomic operation.

## Pattern

Resolve and validate owner-scoped related records before writing. Create the
root and every nested row in one transaction so a later child failure rolls
back the root and all earlier children:

```php
public function handle(Workspace $workspace, CreateShipmentInput $input): Shipment
{
    return DB::transaction(function () use ($workspace, $input): Shipment {
        $status = $this->resolveStatus($workspace, $input->statusId);
        $categories = $this->resolveItemCategories($workspace, $input->items);

        $shipment = $workspace->shipments()->create([
            ...$this->shipmentValues($input),
            'status_id' => $status->id,
        ]);

        foreach ($input->items as $itemInput) {
            $category = $this->categoryFor($categories, $itemInput);

            $shipment->items()->create([
                ...$this->itemValues($itemInput),
                'item_category_id' => $category?->id,
            ]);
        }

        return $shipment;
    });
}
```

Omitted create fields may fall through to documented model defaults. Explicit
`null` remains distinct. A suggested relation may supply a default only when
the input omits that field; it does not prove other optional relationships.

This transaction proves rollback, not serialization. Add row locks only when
the live aggregate participates in the complete existing shared-root protocol.

## Related References

- [`../README.md`](../README.md)
- [`existing-shared-root-lock-protocols-only.md`](existing-shared-root-lock-protocols-only.md)
- [`../../../tests/Integration/Actions/scenario-catalog/aggregate-create-actions.md`](../../../tests/Integration/Actions/scenario-catalog/aggregate-create-actions.md)
- [`../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/nested-defaults-and-rollback.md`](../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/nested-defaults-and-rollback.md)
