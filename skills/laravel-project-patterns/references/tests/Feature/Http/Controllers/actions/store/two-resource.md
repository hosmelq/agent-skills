# Two-Resource Store Tests

## When To Use

Read this reference for a two-resource `store` route or when auditing the matching nested binding depth.

## Pattern

### Two-Resource Route Chain (`workspaces.parent-records.store`)

```php
describe('store', function (): void {
    it('requires authentication', function (): void {
        $workspace = Workspace::factory()->createOne();

        $response = post(route('workspaces.parent-records.store', [
            'workspace' => $workspace,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents creating from an unrelated Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();

        login();

        $response = post(route('workspaces.parent-records.store', [
            'workspace' => $workspace,
        ]));

        $response->assertForbidden();
    });

    it('validates fields', function (array $data, array $expected): void {
        $workspace = Workspace::factory()->createOne();

        login(workspace: $workspace);

        $response = post(route('workspaces.parent-records.store', [
            'workspace' => $workspace,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'enum' => [
            'data' => [
                'example_mode' => 'invalid',
            ],
            'expected' => [
                'example_mode' => 'The selected example mode is invalid.',
            ],
        ],
        'max:255 (string)' => [
            'data' => [
                'name' => Str::repeat('a', 256),
            ],
            'expected' => [
                'name' => 'The name field must not be greater than 255 characters.',
            ],
        ],
        'required' => [
            'data' => [],
            'expected' => [
                'example_mode' => 'The example mode field is required.',
                'name' => 'The name field is required.',
            ],
        ],
    ]);

    it('creates a parent record', function (): void {
        $workspace = Workspace::factory()->createOne();
        $createdParentRecord = ParentRecord::factory()
            ->for($workspace)
            ->createOne();

        login(workspace: $workspace);

        mock(CreateParentRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                Workspace $workspaceArgument,
                CreateParentRecordInput $input
            ): bool => $workspaceArgument->is($workspace)
                && $input->exampleMode === ExampleMode::Primary
                && $input->name === 'Example Parent')
            ->andReturn($createdParentRecord);

        $response = post(route('workspaces.parent-records.store', [
            'workspace' => $workspace,
        ]), [
            'example_mode' => ExampleMode::Primary->value,
            'name' => 'Example Parent',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.show', [
            'workspace' => $workspace,
            'parent_record' => $createdParentRecord,
        ])
            ->assertToast('Parent record created');
    });
});
```

## Related References

- [`../store.md`](../store.md)
