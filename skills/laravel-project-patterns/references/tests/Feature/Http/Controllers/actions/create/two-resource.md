# Two-Resource Create Tests

## When To Use

Read this reference for a two-resource `create` route or when auditing the matching nested binding depth.

## Pattern

### Two-Resource Route Chain (`workspaces.parent-records.create`)

```php
describe('create', function (): void {
    it('requires authentication', function (): void {
        $workspace = Workspace::factory()->createOne();

        $response = get(route('workspaces.parent-records.create', [
            'workspace' => $workspace,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();

        login();

        $response = get(route('workspaces.parent-records.create', [
            'workspace' => $workspace,
        ]));

        $response->assertForbidden();
    });

    it('shows the create parent record page', function (): void {
        $workspace = Workspace::factory()->createOne();

        login(workspace: $workspace);

        $response = get(route('workspaces.parent-records.create', [
            'workspace' => $workspace,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($workspace): void {
                $page->component('parent-records/Create')
                    ->where('workspace.id', $workspace->public_id)
                    ->where('exampleModes', ExampleMode::options());
            });
    });
});
```

## Related References

- [`../create.md`](../create.md)
