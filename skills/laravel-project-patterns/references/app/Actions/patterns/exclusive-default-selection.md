# Exclusive Default Selection

## When To Use

Read this focused reference when the task involves exclusive default selection.

## Pattern

### Exclusive Default Selection

Clear every sibling except the selected row, then select it in one transaction:

```php
public function handle(ChildRecord $childRecord): ChildRecord
{
    return DB::transaction(function () use ($childRecord): ChildRecord {
        ChildRecord::query()
            ->where('parent_record_id', $childRecord->parent_record_id)
            ->whereKeyNot($childRecord->getKey())
            ->update(['is_default' => false]);

        $childRecord->update(['is_default' => true]);

        return $childRecord;
    });
}
```

Do not silently create a replacement default during delete unless that is an
explicit domain contract. Selection, clearing, deletion, and replacement are
different behaviors.

## Related References

- [`../README.md`](../README.md)
