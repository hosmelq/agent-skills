# Parent-Dependent `exists`

## When To Use

Read this focused reference when the task involves parent-dependent `exists`.

## Pattern

### Parent-Dependent `exists`

```php
it('validates option code belongs to the selected parent option', function (): void {
    $workspace = Workspace::factory()->createOne();

    login(workspace: $workspace);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $workspace,
    ]), [
        'parent_option_code' => 'AA',
        'child_option_code' => 'ZZ',
    ]);

    $response->assertRedirectBackWithErrors([
        'child_option_code' => 'The selected child option code is invalid.',
    ]);
});
```

## Related References

- [`../scoped-exists-and-unique.md`](../scoped-exists-and-unique.md)
