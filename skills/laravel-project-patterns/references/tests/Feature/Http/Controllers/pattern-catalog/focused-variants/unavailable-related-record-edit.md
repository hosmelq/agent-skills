# Unavailable Related Record On Edit

## When To Use

Use this leaf when edit keeps an unavailable persisted relation visible.

## Pattern

```php
it('shows the edit page with the stored unavailable related record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $relatedRecord = RelatedRecord::factory()->unavailable()->createOne();

    $parentRecord->update(['related_record_id' => $relatedRecord->id]);

    login(workspace: $parentRecord->workspace);

    $response = get(route('workspaces.parent-records.edit', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page) use ($relatedRecord): void {
            $page->component('parent-records/Edit')
                ->where('relatedOptions.0.value', $relatedRecord->public_id);
        });
});
```

## Related References

- [Parent router](../focused-variant-examples.md)
