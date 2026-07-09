# Route Shape / Action Matrix

## When To Use

Read this focused reference when the task involves route shape / action matrix.

## Pattern

### Route Shape / Action Matrix

| Route shape                    | Actions                                                                      | Reusable test families                                                                                                |
| ------------------------------ | ---------------------------------------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- |
| Settings or singleton route    | single page or mutation                                                      | auth redirect, `403`, page contract, mutation contract                                                                |
| Two-resource `Workspace` chain | resource actions under a `Workspace`                                         | auth, `403`, `Workspace`-scoped `404`, list scoping, validation, redirects/toasts                                     |
| Three-resource route chain     | resource actions under one direct parent                                     | all two-resource families plus direct-parent mismatch, parent-scoped uniqueness, parent-scoped list exclusion         |
| Four-resource route chain      | resource actions under parent and child ancestors                            | outer ancestor `404`, middle parent `404`, leaf `404`, wrong ancestor graph, redundant ownership mismatch             |
| Invokable nested action        | one `__invoke` route                                                         | auth, authorization, binding/scope, success side effect; no artificial CRUD grouping                                  |
| Public JSON endpoint           | session, external-identity, or access-code endpoints without auth middleware | validation first, external verification/domain failures, success JSON, identity/actor/token/notification side effects |
| Protected JSON endpoint        | JSON endpoint requiring auth/token                                           | guest `401`, authenticated success, public-ID JSON contract                                                           |

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
