# Delegated Actions

## When To Use

Read this focused reference when the task involves delegated actions.

## Pattern

### Delegated Actions

When a mutation delegates to a Data input-backed action, construct the action input at the controller boundary from validated input, then pass the typed input to the injected action.

```php
$updateParentRecord->handle(
    $parentRecord,
    UpdateParentRecordInput::from($request->validated()),
);
```

For delegated actions, pass only the models, typed inputs, and scalar values the operation needs as business inputs. This applies equally to top-level and nested resources: `scopeBindings()` plus policy middleware own route hierarchy and ownership. Pass a parent only when the operation independently needs it, such as creation under that parent.

When a delegated action can still throw a domain exception after authorization/validation because it owns a transactional guard, the controller may map that exception to a validation error:

```php
try {
    $updateParentRecord->handle(
        $parentRecord,
        UpdateParentRecordInput::from($request->validated()),
    );
} catch (CannotUpdateParentRecord) {
    throw ValidationException::withMessages([
        'mode' => __('validation.prohibited', ['attribute' => 'mode']),
    ]);
}
```

Keep web/resource request and domain validation in Form Requests. Do not add `ValidationException::withMessages()` in a web controller unless a sibling controller owns the same kind of action-exception mapping. Controller feature tests for these paths prove only HTTP mapping for a mocked action exception; action integration tests prove the transactional guard itself.

## Related References

- [`../README.md`](../README.md)
