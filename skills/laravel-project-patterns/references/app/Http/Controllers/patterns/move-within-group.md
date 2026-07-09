# Move Within An Ordered Group

## When To Use

Use when an invokable member endpoint moves a route-bound record after an
optional move-after record in the same owner-scoped ordered group.

## Pattern

### Route

Put the target in the route and name the operation `move`. Do not submit the
target public ID again in the request body:

```php
Route::patch('workspaces/{workspace}/parent-records/{parent_record}/move', MoveParentRecordController::class)
    ->name('workspaces.parent-records.move');
```

### Controller

Authorize `update` on the bound target. Validation owns owner scope, public-ID
shape, soft-delete exclusion, and self-move rejection. The controller resolves
the optional move-after record within the target's group before invoking the
action:

```php
class MoveParentRecordController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:update,parent_record'),
        ];
    }

    public function __invoke(
        MoveParentRecordRequest $request,
        Workspace $workspace,
        ParentRecord $parentRecord,
        MoveParentRecord $moveParentRecord,
    ): RedirectResponse {
        /** @var array{move_after_id?: null|string} $validated */
        $validated = $request->validated();

        $moveAfterParentRecord = null;

        if (($validated['move_after_id'] ?? null) !== null) {
            $moveAfterParentRecord = $workspace->parentRecords()
                ->where('category', $parentRecord->category)
                ->where('public_id', $validated['move_after_id'])
                ->firstOrFail();
        }

        $moveParentRecord->handle($parentRecord, $moveAfterParentRecord);

        return back()->toast(__('parent_record.moved.title'));
    }
}
```

The target's owner mismatch and soft-deleted state are route-binding failures.
A valid owner-scoped move-after record from another ordered group passes
public-ID validation but fails the controller's group-scoped lookup with `404`.

## Related References

- [`../README.md`](../README.md)
- [`../../Requests/patterns/move-within-group.md`](../../Requests/patterns/move-within-group.md)
- [`references/app/Actions/patterns/group-order-transitions.md`](../../../Actions/patterns/group-order-transitions.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog/move.md`](../../../../tests/Feature/Http/Controllers/pattern-catalog/move.md)
