# State and Order Actions

## When To Use

Use when actions own an exclusive eligible state, state-aware lifecycle
transitions, direct movement within a group, or a grouping-field change that
moves a record between ordered groups.

## Pattern

### Focused References

- [Ensure Exclusive State](state-and-order-actions/ensure-exclusive-state.md): selection when no, one, or historical candidates exist.
- [Explicit Selection](state-and-order-actions/explicit-selection.md): eligibility rejection and single-selection transitions.
- [State-Aware Lifecycle](state-and-order-actions/state-aware-lifecycle.md): deactivation, reactivation, and deletion boundaries.
- [Ordering Actions](state-and-order-actions/ordering.md): Use this leaf for direct move and ordered-group transition scenarios.

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
- [`existing-shared-root-lock-protocol-conditional.md`](existing-shared-root-lock-protocol-conditional.md)
- [`references/app/Actions/patterns/ensure-exclusive-state.md`](../../../../app/Actions/patterns/ensure-exclusive-state.md)
- [`references/app/Actions/patterns/group-order-transitions.md`](../../../../app/Actions/patterns/group-order-transitions.md)
- [`references/app/Actions/patterns/idempotent-lifecycle-flags.md`](../../../../app/Actions/patterns/idempotent-lifecycle-flags.md)
