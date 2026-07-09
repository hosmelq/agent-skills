# Scoped `unique` on Update with `ignore(...)`

## When To Use

Read this focused reference when the task involves scoped `unique` on update with `ignore(...)`.

## Pattern

### Scoped `unique` on Update with `ignore(...)`

```php
it('validates name uniqueness within the same Workspace on update', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'name' => 'Example Name',
    ]);
    $otherParentRecord = ParentRecord::factory()
        ->recycle($parentRecord->workspace)
        ->createOne([
            'name' => 'Other Name',
        ]);

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $otherParentRecord,
    ]), [
        'name' => 'Example Name',
    ]);

    $response->assertRedirectBackWithErrors([
        'name' => 'The name has already been taken.',
    ]);
});
```

```php
it('allows keeping the same name while updating', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'name' => 'Example Name',
    ]);

    login(workspace: $parentRecord->workspace);

    mock(UpdateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            UpdateParentRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->name === 'Example Name');

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'name' => $parentRecord->name,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]);
});
```

## Related References

- [`../scoped-exists-and-unique.md`](../scoped-exists-and-unique.md)
