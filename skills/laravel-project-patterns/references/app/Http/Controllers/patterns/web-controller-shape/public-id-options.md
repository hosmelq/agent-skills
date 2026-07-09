# Public ID Option Lists

## When To Use

Use this leaf when form options expose public IDs.

## Pattern

```php
/**
 * @return Collection<int, array{label: string, value: string}>
 */
private function relatedRecordOptions(Workspace $workspace): Collection
{
    return $workspace->relatedRecords()
        ->active()
        ->orderBy('name')
        ->get()
        ->map(fn (RelatedRecord $relatedRecord): array => [
            'label' => $relatedRecord->name,
            'value' => $relatedRecord->public_id,
        ]);
}
```

## Related References

- [Parent router](../web-controller-shape.md)
