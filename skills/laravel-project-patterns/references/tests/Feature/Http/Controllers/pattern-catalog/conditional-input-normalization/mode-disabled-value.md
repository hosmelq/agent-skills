# Mode-Disabled Value Normalization

## When To Use

Use this leaf when a mode removes a value before mapping.

## Pattern

```php
it('clears the conditional value when its mode is disabled', function (): void {
    $parentRecord = ParentRecord::factory()->enabledMode()->createOne();

    login(workspace: $parentRecord->workspace);

    mock(UpdateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            UpdateParentRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->conditionalValue === null
            && $input->exampleMode === ExampleMode::Disabled);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'example_mode' => ExampleMode::Disabled->value,
    ]);

    $response->assertRedirect();
});

it('preserves the stored conditional value during an unrelated partial update', function (): void {
    $parentRecord = ParentRecord::factory()->enabledMode()->createOne([
        'conditional_value' => '1.0000',
    ]);

    login(workspace: $parentRecord->workspace);

    mock(UpdateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            UpdateParentRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->conditionalValue === '1.0000'
            && $input->minimumValue === '2');

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'minimum_value' => 2,
    ]);

    $response->assertRedirect();
});
```

## Related References

- [Parent router](../conditional-input-normalization.md)
