# Model-Targeted Mutations

## When To Use

Read this focused reference when the task involves model-targeted mutations.

## Pattern

### Model-Targeted Mutations

Update and delete actions receive only the target model and independent business inputs. Test their persistence and business guards here; keep ownership, route hierarchy, authorization, and soft-delete binding coverage at the entrypoint.

Do not assert deletion in delegated controller tests when the action owns the mutation.

```php
it('deletes a leaf record for an active owner', function (): void {
    $leafRecord = LeafRecord::factory()->createOne();

    resolve(DeleteLeafRecord::class)->handle($leafRecord);

    assertSoftDeleted($leafRecord);
});
```

For aggregate deletion, assert every action-owned cascade and any relationship
behavior that must be true after the operation:

```php
it('deletes a parent record and its operational records', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $defaultChildRecord = ChildRecord::factory()
        ->for($parentRecord)
        ->default()
        ->createOne();
    $operationalRecord = OperationalRecord::factory()
        ->for($parentRecord)
        ->createOne();

    resolve(DeleteParentRecord::class)->handle($parentRecord);

    assertSoftDeleted($defaultChildRecord);
    assertSoftDeleted($operationalRecord);
    assertSoftDeleted($parentRecord);

    $defaultChildRecordExists = $parentRecord->defaultChildRecord()->exists();

    expect($defaultChildRecordExists)->toBeFalse();
});
```

## Related References

- [`../README.md`](../README.md)
