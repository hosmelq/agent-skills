# Owner Lifecycle Guards For Nested Mutations

## When To Use

Read this focused reference when the task involves owner lifecycle guards for nested mutations.

## Pattern

### Owner Lifecycle Guards For Nested Mutations

When a create, update, or delete action re-checks an owner lifecycle state under a transaction, cover that guard in the action suite for each mutation shape the action owns. The controller still covers authorization and exception-to-validation mapping.

```php
it('rejects creating a leaf record when the owner is inactive', function (): void {
    $childRecord = ChildRecord::factory()
        ->for(ParentRecord::factory()->inactive())
        ->createOne();

    expect(fn () => resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => '5',
            'minimum_value' => '0',
            'name' => 'Standard Leaf',
            'amount' => '12.50',
        ]),
    ))->toThrow(
        CannotCreateLeafRecord::class,
        'Cannot use an inactive parent record.',
    );

    assertDatabaseMissing(LeafRecord::class, [
        'child_record_id' => $childRecord->id,
        'name' => 'Standard Leaf',
    ]);
});

it('rejects updating a leaf record when the owner is inactive', function (): void {
    $leafRecord = LeafRecord::factory()
        ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
        ->createOne(['name' => 'Original Leaf']);

    expect(fn () => resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from(['name' => 'Updated Leaf']),
    ))->toThrow(
        CannotUpdateLeafRecord::class,
        'Cannot use an inactive parent record.',
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'name' => 'Original Leaf',
    ]);
});

it('rejects deleting a leaf record when the owner is inactive', function (): void {
    $leafRecord = LeafRecord::factory()
        ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
        ->createOne();

    expect(fn () => resolve(DeleteLeafRecord::class)->handle(
        $leafRecord,
    ))->toThrow(
        CannotDeleteLeafRecord::class,
        'Cannot use an inactive parent record.',
    );

    assertNotSoftDeleted($leafRecord);
});
```

## Related References

- [`../README.md`](../README.md)
