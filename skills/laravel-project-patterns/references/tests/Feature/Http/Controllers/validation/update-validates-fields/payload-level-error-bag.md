# Payload-Level Error Bag

## When To Use

Read this focused reference when the task involves payload-level error bag.

## Pattern

### Payload-Level Error Bag

```php
it('requires at least one displayable value', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'contact_email' => '',
        'first_name' => '',
        'last_name' => '',
        'contact_number' => '',
    ]);

    $response->assertRedirectBackWithErrors([
        'summary' => 'Please provide at least one displayable value.',
    ], null, '_general');
});
```

## Related References

- [`../update-validates-fields.md`](../update-validates-fields.md)
