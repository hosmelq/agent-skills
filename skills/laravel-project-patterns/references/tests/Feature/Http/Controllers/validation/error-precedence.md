# Validation Error Precedence

## When To Use

Use when a stored-value-aware `after` validator could add a domain message for
the same field that already failed its base enum, format, or type rule.

## Pattern

Assert that invalid primitive input produces only the primitive rule's error:

```php
it('returns only the enum error before stored-state validation', function (): void {
    $parentRecord = ParentRecord::factory()->selected()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'category' => 'invalid',
    ]);

    $response->assertRedirectBackWithErrors([
        'category' => 'The selected category is invalid.',
    ]);

    $categoryErrors = session('errors')->getBag('default')->get('category');

    expect($categoryErrors)->toBe([
        'The selected category is invalid.',
    ]);
});
```

The Form Request's `after` callback should return early for a field when the
base validator already has an error. Test the valid-but-domain-invalid branch
separately.

## Related References

- [`update-validates-fields/stored-value-comparison.md`](update-validates-fields/stored-value-comparison.md)
- [`references/app/Http/Requests/patterns/cross-field-and-domain-validation.md`](../../../../../app/Http/Requests/patterns/cross-field-and-domain-validation.md)
