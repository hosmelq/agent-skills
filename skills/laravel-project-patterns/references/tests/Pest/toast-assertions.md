# Toast Assertions

## When To Use

Use this leaf for redirect toast assertions.

## Pattern

### Toast Assertions

Use `assertToast()` for redirects that attach the shared toast flash data.

Expected pattern:

```php
$response->assertRedirectToRoute('route.name', [
    'workspace' => $workspace,
])
    ->assertToast('Resource created');
```

The macro asserts the exact flash payload shape under `inertia.flash_data.toast`, including title, variant, timeout, and optional description.

## Related References

- [Parent router](../Pest.md)
