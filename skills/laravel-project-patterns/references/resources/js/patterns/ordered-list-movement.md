# Ordered List Movement

## When To Use

Use when an authored Inertia page moves one route-bound record up or down
within an owner-scoped ordered group.

## Pattern

Import the generated `Move...Controller`, calculate only the adjacent
move-after public ID, and submit that one nullable field. Do not send the target
again in the payload or submit the complete ordered list:

```tsx
import MoveParentRecordController from '#/actions/App/Http/Controllers/MoveParentRecordController'

function move(
  parentRecord: ParentRecordResource,
  groupRecords: ParentRecordResource[],
  direction: 'down' | 'up',
) {
  const currentIndex = groupRecords.findIndex((record) => record.id === parentRecord.id)
  const nextIndex = direction === 'up' ? currentIndex - 1 : currentIndex + 1

  if (currentIndex < 0 || nextIndex < 0 || nextIndex >= groupRecords.length) {
    return
  }

  const moveAfterId =
    direction === 'up'
      ? (groupRecords[nextIndex - 1]?.id ?? null)
      : (groupRecords[nextIndex]?.id ?? null)

  setPendingAction('move')

  router.patch(
    MoveParentRecordController.url({
      workspace,
      parent_record: parentRecord.id,
    }),
    {
      move_after_id: moveAfterId,
    },
    {preserveScroll: true, onFinish: () => setPendingAction(null)},
  )
}
```

Use the same `'move'` pending key to disable both directional controls while
the request is active. The backend remains responsible for owner scope,
self-move rejection, soft deletes, and ordered-group membership.

## Related References

- [`../README.md`](../README.md)
- [`references/app/Http/Controllers/patterns/move-within-group.md`](../../../app/Http/Controllers/patterns/move-within-group.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog/move.md`](../../../tests/Feature/Http/Controllers/pattern-catalog/move.md)
