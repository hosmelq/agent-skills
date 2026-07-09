# Controller Examples

## When To Use

Read this focused reference when the task involves controller examples.

## Pattern

### Controller Examples

Lifecycle controllers expose the HTTP resource and delegate the state
transition to an action. The action owns idempotence and persistence.

```php
class ParentRecordActivationController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:update,parent_record'),
        ];
    }

    public function store(
        Workspace $workspace,
        ParentRecord $parentRecord,
        ActivateParentRecord $activateParentRecord,
    ): RedirectResponse {
        $activateParentRecord->handle($parentRecord);

        return back()->toast(__('parent_record.activated.title'));
    }

    public function destroy(
        Workspace $workspace,
        ParentRecord $parentRecord,
        DeactivateParentRecord $deactivateParentRecord,
    ): RedirectResponse {
        $deactivateParentRecord->handle($parentRecord);

        return back()->toast(__('parent_record.deactivated.title'));
    }
}
```

Complex lifecycle transition with an action:

```php
class ParentRecordDeactivationController implements HasMiddleware
{
    public function store(
        Workspace $workspace,
        ParentRecord $parentRecord,
        DeactivateParentRecord $deactivateParentRecord,
    ): RedirectResponse {
        try {
            $deactivateParentRecord->handle($parentRecord);
        } catch (CannotDeactivateParentRecord) {
            throw ValidationException::withMessages([
                'parent_record' => __('parent_record.validation.active_children'),
            ]);
        }

        return back()->toast(__('parent_record.deactivated.title'));
    }
}
```

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
