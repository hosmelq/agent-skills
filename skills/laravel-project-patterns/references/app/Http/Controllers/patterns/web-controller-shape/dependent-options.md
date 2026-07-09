# Dependent Form Options

## When To Use

Use this leaf for lazy dependent options driven by a query enum.

## Pattern

```php
private function childOptions(
    Request $request,
    Workspace $workspace,
    null|LeafRecord $leafRecord = null,
): Closure {
    return function () use ($request, $workspace, $leafRecord): Collection {
        $selectedParentPublicId = match (
            $request->enum('query', FormQuery::class)
        ) {
            FormQuery::ParentChanged => $request->string('parent_record_id')
                ->toString(),
            default => $leafRecord?->parentRecord?->public_id,
        };

        if ($selectedParentPublicId === null) {
            return collect();
        }

        return $workspace->parentRecords()
            ->active()
            ->where('public_id', $selectedParentPublicId)
            ->firstOrFail()
            ->children()
            ->active()
            ->orderBy('name')
            ->get()
            ->map(fn (ChildRecord $childRecord): array => [
                'label' => $childRecord->name,
                'value' => $childRecord->public_id,
            ]);
    };
}
```

## Related References

- [Parent router](../web-controller-shape.md)
