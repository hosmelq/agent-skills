# Stored Cross-Field Comparison

## When To Use

Use this leaf when request-owned validation compares submitted and stored values.

## Pattern

### Stored-Bound Cross-Field Validation

Use this only when the request owns the comparison and does not need
action-owned dependent state.

```php
it('validates minimum value against the stored maximum value', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'minimum_value' => 3,
        'maximum_value' => 5,
    ]);

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'minimum_value' => 6,
    ]);

    $response->assertRedirectBackWithErrors([
        'minimum_value' => 'The minimum value field must be less than or equal to 5.',
    ]);
});

it('validates maximum value against the stored minimum value', function (): void {
    $leafRecord = LeafRecord::factory()->createOne([
        'minimum_value' => 10,
        'maximum_value' => 15,
    ]);

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'maximum_value' => 5,
    ]);

    $response->assertRedirectBackWithErrors([
        'maximum_value' => 'The maximum value field must be greater than or equal to 10.',
    ]);
});
```

## Related References

- [Parent router](../stored-and-normalized-values.md)
