# Data Input Actions: Create Required Fields And Defaults

## When To Use

Read this leaf when create required fields and defaults is the action-owned contract.

## Pattern

For create inputs, include a focused default/required-only case only when omitted `Optional` fields should fall through to model `$attributes` defaults or the action otherwise owns omission behavior. Do not add a required-only create test just because another action has one, and do not put that test in a controller feature test unless the controller boundary itself handles omission. For update inputs, cover full updates, partial updates where omitted fields remain unchanged, and nullable fields clearing with explicit `null`.

```php
it('creates a child record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    $childRecord = resolve(CreateChildRecord::class)->handle(
        $parentRecord,
        CreateChildRecordInput::from([
            'description' => 'Primary child record',
            'name' => 'Primary Child',
        ]),
    );

    assertDatabaseHas(ChildRecord::class, [
        'id' => $childRecord->id,
        'parent_record_id' => $parentRecord->id,
        'description' => 'Primary child record',
        'name' => 'Primary Child',
    ]);
});

it('creates a parent record with only required fields', function (): void {
    $workspace = Workspace::factory()->createOne();

    $parentRecord = resolve(CreateParentRecord::class)->handle(
        $workspace,
        CreateParentRecordInput::from([
            'name' => 'Required Parent',
            'status' => ParentRecordStatus::Active(),
        ]),
    );

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'workspace_id' => $workspace->id,
        'description' => null,
        'name' => 'Required Parent',
        'status' => ParentRecordStatus::Active,
    ]);
});
```

## Related References

- [`../data-input-actions.md`](../data-input-actions.md)
