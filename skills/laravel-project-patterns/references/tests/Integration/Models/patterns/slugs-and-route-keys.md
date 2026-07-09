# Slugs And Route Keys

## When To Use

Use this leaf for persisted slug and route-key behavior.

## Pattern

### Slugs And Route Keys

Slug generation and non-regeneration on update belong here when implemented by model attributes or observers.

```php
it('generates a slug when creating a Workspace', function (): void {
    $workspace = Workspace::factory()->createOne([
        'name' => 'Example Workspace',
    ]);

    assertDatabaseHas(Workspace::class, [
        'id' => $workspace->id,
        'slug' => 'example-workspace',
    ]);
});

it('does not regenerate the slug when the Workspace name changes', function (): void {
    $workspace = Workspace::factory()->createOne([
        'name' => 'Example Workspace',
    ]);

    $workspace->update([
        'name' => 'Updated Workspace',
    ]);

    assertDatabaseHas(Workspace::class, [
        'id' => $workspace->id,
        'slug' => 'example-workspace',
    ]);
});
```

## Related References

- [Parent router](../README.md)
