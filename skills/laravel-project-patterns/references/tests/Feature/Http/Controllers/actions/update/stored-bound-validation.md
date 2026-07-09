# Stored-Bound Update Validation

## When To Use

Read this reference only when the Form Request safely owns a stored-value comparison without action-owned locking.

## Pattern

### Stored-Bound Validation Example

Use this shape only when the Form Request owns the comparison and can safely evaluate it without action-owned locks.

```php
it('validates minimum value against the stored maximum value', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'maximum_value' => 5,
        'minimum_value' => 3,
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

- [`../update.md`](../update.md)
