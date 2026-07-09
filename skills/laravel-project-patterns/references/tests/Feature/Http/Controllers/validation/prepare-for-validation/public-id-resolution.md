# Public-ID Validation and Resolution

## When To Use

Read this leaf when a form submits a related record by public ID and the
delegated action resolves that ID inside the owner scope.

## Pattern

### Public-ID Validation

```php
it('validates fields', function (array $data, array $expected): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = post(route('workspaces.parent-records.children.store', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'exists' => [
        'data' => [
            'related_record_id' => 'not-a-public-id',
        ],
        'expected' => [
            'related_record_id' => 'The selected related record id is invalid.',
        ],
    ],
]);
```

### Delegated Public-ID Mapping

```php
it('creates a child record with a related record public id', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $relatedRecord = RelatedRecord::factory()
        ->for($parentRecord->workspace)
        ->createOne();
    $createdChildRecord = ChildRecord::factory()
        ->for($parentRecord)
        ->createOne();

    login(workspace: $parentRecord->workspace);

    mock(CreateChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            CreateChildRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->relatedRecordId === $relatedRecord->public_id)
        ->andReturn($createdChildRecord);

    $response = post(route('workspaces.parent-records.children.store', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'related_record_id' => $relatedRecord->public_id,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.children.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
        'child_record' => $createdChildRecord,
    ]);
});
```

The controller test proves the public ID reaches the typed input. The action
integration test proves owner-scoped resolution and the internal database
column.

## Related References

- [`../prepare-for-validation.md`](../prepare-for-validation.md)
- [`../../../../../Integration/Actions/patterns/data-input-actions.md`](../../../../../Integration/Actions/patterns/data-input-actions.md)
