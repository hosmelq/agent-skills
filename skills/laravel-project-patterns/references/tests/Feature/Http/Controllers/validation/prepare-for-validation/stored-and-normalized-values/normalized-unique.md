# Normalized Unique Validation

## When To Use

Use this leaf when input is normalized before unique validation.

## Pattern

### Normalized Value Before Unique Validation

```php
it('validates contact value uniqueness using the normalized value', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'contact_value' => '+10000000000',
    ]);

    login(workspace: $parentRecord->workspace);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $parentRecord->workspace,
    ]), [
        'contact_value' => '+1 000 000 0000',
    ]);

    $response->assertRedirectBackWithErrors([
        'contact_value' => 'The contact value has already been taken.',
    ]);
});
```

Action-owned dependent-state guards belong in actions; the controller keeps
only mocked exception-to-validation mapping for those branches.

## Related References

- [Parent router](../stored-and-normalized-values.md)
