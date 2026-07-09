# Route-Bound Move Target And Access

## When To Use

Use for authentication, target authorization, scoped binding, and soft-delete
coverage after the move target becomes a member-route parameter.

## Pattern

```php
it('requires authentication', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertRedirectToRoute('login');
});

it('prevents moving for an unrelated workspace', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login();

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertForbidden();
});

it('returns not found when the target belongs to another workspace', function (): void {
    $workspace = Workspace::factory()->createOne();
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertNotFound();
});

it('returns not found when the target is soft deleted', function (): void {
    $parentRecord = ParentRecord::factory()->trashed()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertNotFound();
});
```

## Related References

- [Parent router](../move.md)
- [`references/tests/Feature/Http/Controllers/route-patterns/selection-and-binding.md`](../../route-patterns/selection-and-binding.md)
