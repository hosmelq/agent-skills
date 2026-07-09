# Soft-Deleted Related Record On Show

## When To Use

Use this leaf for show behavior involving a soft-deleted related record.

## Pattern

```php
it('shows the record with the stored soft-deleted related record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $relatedRecord = RelatedRecord::factory()
        ->trashed()
        ->createOne();

    $parentRecord->update(['related_record_id' => $relatedRecord->id]);

    login(workspace: $parentRecord->workspace);

    $response = get(route('workspaces.parent-records.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page) use ($relatedRecord): void {
            $page->component('parent-records/Show')
                ->where('relatedRecord.deleted_at', $relatedRecord->deleted_at->toJSON())
                ->where('relatedRecord.id', $relatedRecord->public_id);
        });
});
```

## Related References

- [Parent router](../focused-variant-examples.md)
