# Ordering Actions

## When To Use

Use this leaf for direct move and ordered-group transition scenarios.

## Pattern

### Ordering

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | moves a record after another record in the same owner and group | exact resulting order |
| 2 | moves a record to the start when no move-after record is supplied | nullable move-after branch and exact resulting order |
| 3 | moves an inactive record when inactive rows remain ordered | inactive target participates in the exact resulting order |
| 4 | moves an updated record to the end of the destination group | old group is normalized, destination is owner-scoped, and target is last |
| 5 | assigns order one when the destination group is empty | empty-group boundary |
| 6 | leaves order unchanged when the grouping field is omitted | partial update does not trigger regrouping |

When the controller delegates move persistence, prove the complete order and
other-group isolation in the action integration suite:

```php
it('moves a record after another record within its group', function (): void {
    $workspace = Workspace::factory()->createOne();
    $firstParentRecord = ParentRecord::factory()->for($workspace)->firstGroup()->createOne();
    $secondParentRecord = ParentRecord::factory()->for($workspace)->firstGroup()->createOne();
    $thirdParentRecord = ParentRecord::factory()->for($workspace)->firstGroup()->createOne();
    $otherGroupParentRecord = ParentRecord::factory()->for($workspace)->secondGroup()->createOne();

    resolve(MoveParentRecord::class)->handle($firstParentRecord, $secondParentRecord);

    $firstGroupIds = $workspace->parentRecords()
        ->firstGroup()
        ->ordered()
        ->pluck('id')
        ->all();

    $secondGroupIds = $workspace->parentRecords()
        ->secondGroup()
        ->ordered()
        ->pluck('id')
        ->all();

    expect($firstGroupIds)->toBe([
        $secondParentRecord->id,
        $firstParentRecord->id,
        $thirdParentRecord->id,
    ])->and($secondGroupIds)->toBe([$otherGroupParentRecord->id]);
});
```

Keep the inactive-target contract as its own action case:

```php
it('moves an inactive record within its group', function (): void {
    $workspace = Workspace::factory()->createOne();
    $activeParentRecord = ParentRecord::factory()->for($workspace)->firstGroup()->createOne();
    $inactiveParentRecord = ParentRecord::factory()->for($workspace)->firstGroup()->inactive()->createOne();

    resolve(MoveParentRecord::class)->handle($inactiveParentRecord, null);

    $parentRecordIds = $workspace->parentRecords()
        ->firstGroup()
        ->ordered()
        ->pluck('id')
        ->all();

    expect($parentRecordIds)->toBe([
        $inactiveParentRecord->id,
        $activeParentRecord->id,
    ]);
});
```

These tests prove business state. Add SQL lock assertions only when the live
actions participate in the complete protocol described by
`existing-shared-root-lock-protocol-conditional.md`.

## Related References

- [Parent router](../state-and-order-actions.md)
