# Conditional Store Input Normalization

## When To Use

Use when a store request accepts a conditional value but must discard it when
the submitted mode disables that value. Unlike update, this mapping has no
stored fallback.

## Pattern

Prove that the request maps a submitted but inapplicable value to explicit
`null` before constructing the create input:

```php
it('clears the conditional value on create when its mode is disabled', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $createdChildRecord = ChildRecord::factory()
        ->for($parentRecord)
        ->createOne();

    login(workspace: $parentRecord->workspace);

    mock(CreateChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            CreateChildRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->conditionalValue === null
            && $input->exampleMode === ExampleMode::Disabled)
        ->andReturn($createdChildRecord);

    $response = post(route('workspaces.parent-records.children.store', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'conditional_value' => 1,
        'example_mode' => ExampleMode::Disabled->value,
        'name' => 'Example',
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.children.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
        'child_record' => $createdChildRecord,
    ]);
});
```

Keep this separate from update cases that preserve a stored conditional value
when both the mode and conditional value are omitted.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
- [`conditional-input-normalization.md`](conditional-input-normalization.md)
- [`references/app/Http/Requests/patterns/normalization.md`](../../../../../app/Http/Requests/patterns/normalization.md)
