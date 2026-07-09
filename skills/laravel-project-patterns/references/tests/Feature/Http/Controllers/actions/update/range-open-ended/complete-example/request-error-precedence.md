# Complete Range And Open-Ended Update Example: Request Error Precedence

## When To Use

Read this leaf when request error precedence is in scope for Complete Range And Open-Ended Update Example.

## Pattern

```php
it('does not validate range availability when other fields are invalid', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(0, 5)
        ->createOne();

    LeafRecord::factory()
        ->recycle($leafRecord->childRecord)
        ->forRange(10, null)
        ->createOne();

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'name' => '',
        'maximum_value' => null,
    ]);

    $response->assertRedirectBackWithErrors([
        'name' => 'The name field is required.',
    ])
        ->assertSessionDoesntHaveErrors(['maximum_value', 'minimum_value']);
});
```

## Related References

- [`../complete-example.md`](../complete-example.md)
