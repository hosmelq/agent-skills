# Composed Scoped Create

## When To Use

Read this focused reference when the task involves composed scoped create.

## Pattern

### Composed Scoped Create

A composed create may derive the owner, resolve a submitted public ID inside
that owner, apply reusable active-state guards, replace interface values with
internal keys, generate a value, and persist denormalized ownership atomically:

```php
public function __construct(
    private readonly EnsureRecordIsActive $ensureRecordIsActive,
    private readonly GenerateLeafCode $generateLeafCode,
) {
}

public function handle(
    ChildRecord $childRecord,
    CreateLeafRecordInput $input,
): LeafRecord {
    return DB::transaction(function () use ($childRecord, $input): LeafRecord {
        $parentRecord = $this->ensureRecordIsActive->handle(
            $childRecord->parentRecord,
        );
        $workspace = $parentRecord->workspace;
        $attributes = $input->transform();

        /** @var string $relatedRecordPublicId */
        $relatedRecordPublicId = $attributes['related_record_id'];

        $relatedRecord = $this->ensureRecordIsActive->handle(
            $workspace->relatedRecords()
                ->where('public_id', $relatedRecordPublicId)
                ->firstOrFail(),
        );

        $attributes['related_record_id'] = $relatedRecord->id;

        return $childRecord->leaves()->create([
            ...$attributes,
            'workspace_id' => $workspace->id,
            'code' => $this->generateLeafCode->handle($childRecord),
        ]);
    });
}
```

The submitted owner or related public ID is not trusted as an internal key.
Cross-owner, missing, or inactive related records fail before generation and
persistence; collaborator assertions belong in the action integration suite.

## Related References

- [`../README.md`](../README.md)
