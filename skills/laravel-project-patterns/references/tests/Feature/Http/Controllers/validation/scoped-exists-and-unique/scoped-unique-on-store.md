# Scoped `unique` on Store

## When To Use

Read this focused reference when the task involves scoped `unique` on store.

## Pattern

### Scoped `unique` on Store

```php
it('validates name uniqueness within the same Workspace', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'name' => 'Example Name',
    ]);

    login(workspace: $parentRecord->workspace);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $parentRecord->workspace,
    ]), [
        'name' => 'Example Name',
    ]);

    $response->assertRedirectBackWithErrors([
        'name' => 'The name has already been taken.',
    ]);
});
```

```php
it('allows using the same name in another Workspace', function (): void {
    ParentRecord::factory()->createOne([
        'name' => 'Example Name',
    ]);
    $workspace = Workspace::factory()->createOne();
    $createdParentRecord = ParentRecord::factory()
        ->for($workspace)
        ->createOne();

    login(workspace: $workspace);

    mock(CreateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            Workspace $workspaceArgument,
            CreateParentRecordInput $input
        ): bool => $workspaceArgument->is($workspace)
            && $input->name === 'Example Name')
        ->andReturn($createdParentRecord);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $workspace,
    ]), [
        'name' => 'Example Name',
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.show', [
        'workspace' => $workspace,
        'parent_record' => $createdParentRecord,
    ]);
});
```

```php
it('allows reusing a name after the existing record is deleted', function (): void {
    $workspace = Workspace::factory()->createOne();
    ParentRecord::factory()
        ->for($workspace)
        ->trashed()
        ->createOne([
            'name' => 'Example Name',
        ]);
    $createdParentRecord = ParentRecord::factory()
        ->for($workspace)
        ->createOne();

    login(workspace: $workspace);

    mock(CreateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            Workspace $workspaceArgument,
            CreateParentRecordInput $input
        ): bool => $workspaceArgument->is($workspace)
            && $input->name === 'Example Name')
        ->andReturn($createdParentRecord);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $workspace,
    ]), [
        'name' => 'Example Name',
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.show', [
        'workspace' => $workspace,
        'parent_record' => $createdParentRecord,
    ]);
});
```

## Related References

- [`../scoped-exists-and-unique.md`](../scoped-exists-and-unique.md)
