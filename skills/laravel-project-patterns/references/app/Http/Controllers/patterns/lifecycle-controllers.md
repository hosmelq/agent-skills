# Lifecycle Controllers

## When To Use

Read this focused reference when the task involves lifecycle controllers.

## Pattern

### Lifecycle Controllers

For custom verbs such as activate/deactivate, enable/disable, approve/reject, confirm/unconfirm, regenerate, login/logout, subscribe/unsubscribe, print, search, export, or resend, load `lifecycle-resources.md` and model the operation as a resourceful controller before adding custom controller methods.

Lifecycle controllers should still use the HTTP boundary shape above: middleware, route models, optional request marker classes, action delegation when needed, redirect/back, and toast.

## Related References

- [`../README.md`](../README.md)
