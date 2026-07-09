# Store General Error-Bag Validation

## When To Use

Use this leaf for a named general-error-bag payload failure.

## Pattern

```php
it('requires at least one displayable value', function (): void {
    $workspace = Workspace::factory()->createOne();

    login(workspace: $workspace);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $workspace,
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

- [Parent router](../store-validates-fields.md)
