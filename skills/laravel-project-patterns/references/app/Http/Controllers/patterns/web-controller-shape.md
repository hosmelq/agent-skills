# Web Controller Shape

## When To Use

Read this focused reference when the task involves web controller shape.

## Pattern

### Web Controller Shape

- Web controllers usually implement `HasMiddleware`.
- Authorize with `new Middleware('can:...', only: [...])` when sibling controllers do.
- Return `Inertia::render(...)` for pages and redirects for mutations.
- Inertia modal support is frontend-layout driven unless sibling route/controller code already uses `Inertia::modal(...)`.
- Use model resources with `->toResource()` and collections with `->toResourceCollection()`.
- Query list pages from the owning relationship, usually ordered with `latest('id')->paginate()`.
- Use owning relationships for creates and lists that establish `Workspace` ownership.
- For member actions on already-bound child models, trust scoped binding and policies, then delegate mutations to an action.
- Redirect with named routes and attach toasts through the shared redirect macro.
- Preserve route-model binding, scoped binding, and policy-masked not-found behavior from sibling controllers. Wrong parent/child chains should fail as binding `404` or policy `denyAsNotFound()` according to the live route shape.
- Do not query a child independently when route binding already scopes it. Use the bound model after binding, policy, and validation have passed.
- For children with redundant `Workspace` columns, preserve the extra guard used by siblings: list from the owning relationship and add the `Workspace` filter where the child stores the `Workspace` independently. For member actions on already-bound models, the equivalent guard may live in the policy as a not-found denial.
- Keep option lists shaped as `label`/`value` collections using public IDs for form values when the UI contract exposes public IDs.
- Do not silently hide deactivated records from web lists unless sibling code does. Deactivation is a nullable lifecycle timestamp distinct from soft deletion; soft-deleted records stay excluded by binding and `withoutTrashed()` rules.

For Data input-backed create/update mutations, keep submitted collaborator
public IDs in the typed action input when the action owns scoped active-state
or domain resolution. Do not resolve the same value in both layers. A focused
move endpoint is a separate contract: its request validates `move_after_id`
and its controller resolves the optional route-scoped predecessor before
calling the move action.

Option list helpers should return public IDs when the form posts public IDs:

[Public ID Option Lists](web-controller-shape/public-id-options.md)

Dependent form options may be lazy closures driven by a query enum. On edit,
fall back to the persisted relation when no new parent selection was requested:

[Dependent Form Options](web-controller-shape/dependent-options.md)

The controller returns public IDs and owner-scoped active options. The frontend
uses a partial reload and clears or remounts the stale child selection.

## Related References

- [`../README.md`](../README.md)
