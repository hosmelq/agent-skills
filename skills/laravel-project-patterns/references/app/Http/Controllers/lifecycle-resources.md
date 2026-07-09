# Lifecycle Resource Controllers

## Purpose

Define controller naming and routing conventions for lifecycle operations that are often first described as verbs: activate/deactivate, enable/disable, approve/reject, confirm/unconfirm, login/logout, subscribe/unsubscribe, regenerate, resend, print, search, export, archive/restore, suspend/unsuspend, publish/unpublish, and similar actions.

The goal is to keep controllers CRUDdy: pick the resource being created, shown, updated, or destroyed, then use standard methods (`index`, `show`, `create`, `store`, `edit`, `update`, `destroy`) instead of adding custom action methods to an existing resource controller.

## When To Use

Use this reference when a controller or route proposal includes a non-resourceful method name such as:

- `activate`, `deactivate`, `enable`, `disable`, `toggle`
- `approve`, `reject`, `confirm`, `unconfirm`, `verify`, `unverify`
- `login`, `logout`, `authenticate`, `register`, `subscribe`, `unsubscribe`
- `regenerate`, `resend`, `print`, `search`, `export`, `archive`, `restore`
- `markAs...`, `set...`, `make...`, `send...`, `generate...`

Also use it when adding a nullable lifecycle timestamp such as `deactivated_at`, a state-management route, a controller that mutates a single attribute, or a UI action that changes a model's lifecycle state.

## Required Pattern

### Reference Map

- [`lifecycle-resources/core-rule.md`](lifecycle-resources/core-rule.md): Core Rule.
- [`lifecycle-resources/naming-decision-tree.md`](lifecycle-resources/naming-decision-tree.md): Naming Decision Tree.
- [`lifecycle-resources/route-patterns.md`](lifecycle-resources/route-patterns.md): Route Patterns.
- [`lifecycle-resources/controller-examples.md`](lifecycle-resources/controller-examples.md): Controller Examples.
- [`lifecycle-resources/authorization-pattern.md`](lifecycle-resources/authorization-pattern.md): Authorization Pattern.
- [`lifecycle-resources/data-modeling-pattern.md`](lifecycle-resources/data-modeling-pattern.md): Data Modeling Pattern.

## Coverage Expectations

Read the controller, route, action, request, and sibling controller files that define the lifecycle surface. Cover HTTP behavior in feature tests and action-owned transactional guards in action integration tests.

## Do Not

- Do not add custom verb methods to an existing resource controller when a resourceful lifecycle controller fits.
- Do not use `forceFill(...)->save()` for ordinary lifecycle mutations.

## Related References

- [`references/app/Http/Controllers/README.md`](README.md)
- [`references/app/Actions/README.md`](../../Actions/README.md)
- [`references/tests/Feature/Http/Controllers/route-patterns.md`](../../../tests/Feature/Http/Controllers/route-patterns.md)
