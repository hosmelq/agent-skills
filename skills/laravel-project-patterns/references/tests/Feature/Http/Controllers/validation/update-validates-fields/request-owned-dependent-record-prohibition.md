# Request-Owned Dependent Record Prohibition

## When To Use

Read this focused reference when the task involves request-owned dependent record prohibition.

## Pattern

### Request-Owned Dependent Record Prohibition

Use this shape only when the Form Request owns the rule before the action runs. If the guard needs action-owned locks, transactional reads, or exception mapping from a delegated action, prove the guard in `tests/Integration/Actions` and keep the controller test focused on mocked exception-to-validation mapping.

```php
it('validates a field is prohibited when dependent records exist', function (): void {
    $leafRecord = LeafRecord::factory()->createOne();

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'locked_value' => 'changed',
    ]);

    $response->assertRedirectBackWithErrors([
        'locked_value' => 'The locked value field is prohibited.',
    ]);
});
```

## Related References

- [`../update-validates-fields.md`](../update-validates-fields.md)
