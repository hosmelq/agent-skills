# Stored-Value Comparison

## When To Use

Read this focused reference when the task involves stored-value comparison.

## Pattern

### Stored-Value Comparison

```php
it('validates minimum value against stored maximum value', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'minimum_value' => 2,
        'maximum_value' => 5,
    ]);

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'minimum_value' => 6,
    ]);

    $response->assertRedirectBackWithErrors([
        'minimum_value' => 'The minimum value field must be less than or equal to 5.',
    ]);
});
```

## Related References

- [`../update-validates-fields.md`](../update-validates-fields.md)
