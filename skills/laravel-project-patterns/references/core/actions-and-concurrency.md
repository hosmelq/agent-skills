# Action And Concurrency Contracts

## When To Use

Use this leaf when deciding an action signature, querying a passed model,
enforcing an invariant, or considering any concurrency mechanism.

## Pattern

- Actions must accept only the models, payloads, and independent values that are business inputs to the operation. Do not pass route hierarchy solely to repeat ownership checks already enforced by scoped bindings and policies at the entrypoint.
- Do not re-query a model passed to an action solely to prove ownership, existence, or soft-delete state. Query fresh state only when the action owns a required relationship read or an already-justified transactional guard or lock; this exception is not permission to introduce a lock.
- A parent or owner belongs in the action signature only when the operation needs it as a business input, such as creating a row through that parent. Otherwise derive required business relationships from the target model instead of making callers reconstruct the route hierarchy.
- Default to no explicit row lock. A transaction, multiple writes, an active-state check, move/order behavior, or initial/default selection does not by itself justify `lockForUpdate()`.
- Prefer database constraints for invariants that PostgreSQL can enforce. Add an explicit lock only for a single-row consume/recheck transition whose competing consumers all use that path, or after identifying a documented cross-row invariant that cannot reasonably live in the database, enumerating every competing action, and proving that all competitors lock the same stable row in the same order before reading or writing dependent state. If neither complete protocol already exists or can be established, keep the action direct and do not infer a lock from a reference example.
- Rejecting or declining a lock recommendation does not authorize an improvised replacement concurrency protocol. Do not add conditional `UPDATE ... WHERE` writes, affected-row branching, follow-up `exists()` or fresh-state queries, retries, advisory locks, or similar mechanisms solely to approximate the rejected lock or satisfy a review comment. Any such mechanism needs independent evidence from the live contract, a named invariant and competing operations, defined conflict/idempotency semantics, and focused tests. Without that evidence, preserve the direct action and classify the recommendation as not applicable.

## Related References

- [`references/app/Actions/README.md`](../app/Actions/README.md)
- [`existing-shared-root-lock-protocols-only.md`](../app/Actions/patterns/existing-shared-root-lock-protocols-only.md)
- [`references/tests/Integration/Actions/README.md`](../tests/Integration/Actions/README.md)
