# Application HTTP Controllers

## When To Use

Use this leaf when a change crosses controller concerns before selecting the focused controller router.

## Pattern

### Controllers

- Web controllers typically implement `HasMiddleware` and return a static `middleware(): array` with `new Middleware('can:...', only: [...])` for authorization.
- Web CRUD actions return Inertia responses for read/create/edit/index/show and redirects for mutations.
- Use `Inertia::render('pages/...', [...])` for pages; keep resources and option lists shaped in the controller exactly like siblings.
- Use Eloquent resources with `->toResource()` and collections with `->toResourceCollection()` where sibling controllers do.
- Mutations usually create, update, or delete through the owning relationship to preserve `Workspace` ownership.
- Redirect with named routes via `to_route(...)`, then attach toasts with the shared `toast()` macro.
- When a mutation delegates persistence to an action with a Data input, construct the input from validated request data at the controller boundary and pass it to the injected action.
- For delegated top-level mutations on an already-bound model, pass the model and input the action actually needs. Do not pass the route `Workspace` or parent only so the action can re-query ownership already enforced by scoped bindings and policy middleware.
- For delegated nested creates, pass the direct parent when creation through that relationship is part of the operation. For update, delete, and lifecycle mutations, pass the bound child directly unless another model is an independent business input; scoped bindings and policies own the HTTP route hierarchy.
- Query list pages with local ordering and pagination conventions such as `latest('id')->paginate()`.
- Preserve route-model binding semantics from `Route::scopeBindings()`. If an ancestor or child does not belong to the route chain, feature tests should assert `404`, not manually authorize it.
- For nested controllers, include route parameters in the same order used by the route name and sibling tests: `Workspace` or top-level parent first, then each ancestor, then the leaf model.
- For fields submitted as public IDs but stored as internal IDs, validate against `public_id` and convert after validation at the controller boundary when the live controller pattern does that.
- API controllers use `getJson()`/`postJson()` tests, resource responses, `JsonResponse`, validation exceptions, HTTP fakes, and token assertions as appropriate.

## Related References

- [Parent router](../http-layer.md)
