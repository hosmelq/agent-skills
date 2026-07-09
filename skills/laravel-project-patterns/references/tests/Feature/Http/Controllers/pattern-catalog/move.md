# Move Endpoint

## When To Use

Use when a member endpoint moves a route-bound record within an owner-scoped
ordered group by accepting only a nullable move-after public ID. The target
belongs in the route, not in the request payload. Match invokable versus named
controller shape to the live route and equivalent move-endpoint family.

## Pattern

Keep this order:

1. requires authentication;
2. prevents moving a target for an unrelated `Workspace`;
3. returns `404` when the route-bound target belongs to another `Workspace`;
4. returns `404` for a soft-deleted route-bound target;
5. rejects moving the target after itself with `Rule::notIn(...)`;
6. validates the nullable `move_after_id` as a string;
7. rejects a move-after record from another `Workspace`;
8. rejects a soft-deleted move-after record;
9. returns `404` when the move-after record belongs to another ordered group;
10. passes the bound target and resolved move-after record to `Move...`;
11. passes `null` when the target moves to the group start;
12. accepts an inactive target when inactive rows remain ordered.

Load only the focused branch being changed:

- [Route-Bound Target And Access](move/route-bound-target-and-access.md)
- [Self And Type Validation](move/self-and-type-validation.md)
- [Move-After Scope Validation](move/move-after-scope-validation.md)
- [Move-After Group Boundary](move/move-after-group-boundary.md)

When the controller delegates persistence, the primary success proves only the
HTTP-to-action boundary, redirect, and toast:

[Delegated Move Success](move/delegated-success.md)

When the live contract supports moving to the start, keep the nullable-
move-after success:

[Move To Group Start](move/to-group-start.md)

When it also accepts inactive targets, keep a separate inactive-target
success; it proves that binding, authorization, and request
validation allow that model state to reach the mocked action. Neither case is a
duplicate of moving an active target after an active move-after record, and an
action integration test cannot replace either HTTP path. If inactive targets
are not accepted, do not add this success case; keep the applicable rejection
instead. Exact order, inactive-row participation, group normalization, and
other-group isolation belong to action integration tests.

[Inactive Move Target At Start](move/inactive-target-at-start.md)

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
- [`references/tests/Feature/Http/Controllers/validation/dataset-catalog/public-id-and-managed-inputs.md`](../validation/dataset-catalog/public-id-and-managed-inputs.md)
- [`references/app/Http/Controllers/patterns/move-within-group.md`](../../../../../app/Http/Controllers/patterns/move-within-group.md)
- [`references/app/Http/Requests/patterns/move-within-group.md`](../../../../../app/Http/Requests/patterns/move-within-group.md)
- [`references/app/Actions/patterns/group-order-transitions.md`](../../../../../app/Actions/patterns/group-order-transitions.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../../Integration/Actions/scenario-catalog/state-and-order-actions.md)
