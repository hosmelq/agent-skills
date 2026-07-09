# Dependent Options Reload

## When To Use

Use this leaf for a partial reload of dependent form options.

## Pattern

```php
it('loads related options for the selected parent option', function (): void {
    $workspace = Workspace::factory()->createOne();
    $relatedOption = RelatedOption::factory()->createOne();

    login(workspace: $workspace);

    $response = get(route('workspaces.parent-records.create', [
        'workspace' => $workspace,
        'parent_option' => $relatedOption->parent_option,
    ]));

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page) use ($relatedOption): void {
            $page->component('parent-records/Create')
                ->where('parentOption', $relatedOption->parent_option)
                ->reloadOnly('relatedOptions', function (AssertableInertia $reload) use ($relatedOption): void {
                    $reload->where('relatedOptions.0.value', $relatedOption->public_id);
                });
        });
});
```

## Related References

- [Parent router](../focused-variant-examples.md)
