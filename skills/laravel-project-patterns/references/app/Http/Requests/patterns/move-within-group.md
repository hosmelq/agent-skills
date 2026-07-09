# Move Within Group Request

## When To Use

Use when the move target is route-bound and the payload contains only a
nullable public ID identifying the record to move after.

## Pattern

```php
public function rules(
    #[RouteParameter('workspace')] Workspace $workspace,
    #[RouteParameter('parent_record')] ParentRecord $parentRecord
): array {
    return [
        'move_after_id' => [
            'nullable',
            'string',
            Rule::notIn([$parentRecord->public_id]),
            Rule::exists(ParentRecord::class, 'public_id')
                ->where('workspace_id', $workspace->id)
                ->withoutTrashed(),
        ],
    ];
}
```

Do not add a body-level target ID or a cross-field `different:` rule. Route
binding owns the target; `Rule::notIn(...)` rejects the bound target's public
ID. Keep group membership out of this `exists` rule when the controller must
distinguish an owner-valid but wrong-group move-after record as a `404`.

## Related References

- [`../README.md`](../README.md)
- [`../../Controllers/patterns/move-within-group.md`](../../Controllers/patterns/move-within-group.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog/move.md`](../../../../tests/Feature/Http/Controllers/pattern-catalog/move.md)
