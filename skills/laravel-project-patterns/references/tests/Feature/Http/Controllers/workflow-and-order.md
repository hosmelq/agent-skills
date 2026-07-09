# Controller Test Workflow And Order

## When To Use

Use this leaf to select controller-test evidence and place cases in canonical order.

## Pattern

### Quick Start

```bash
php artisan make:test --pest Http/Controllers/<Name>ControllerTest --no-interaction
php artisan test --compact tests/Feature/Http/Controllers/<Name>ControllerTest.php
php artisan test --compact --filter="<test name>"
```


### Decision Workflow

1. Inspect the route definition, controller action, form request, policy,
   resource, and equivalent live sibling tests with the same behavior and
   ownership boundary.
2. Choose the transport: web/session or JSON API.
3. Determine route shape, route parameter keys, public route keys, and every scoped-binding boundary.
4. Apply the action order from the matching action reference:
   - authentication;
   - authorization (`403`);
   - route binding and ownership mismatch (`404`) from outer ancestor to direct parent to leaf;
   - soft-deleted ancestor or leaf `404` beside the boundary it belongs to;
   - lifecycle or state guards that stop before validation;
   - validation datasets and named validation cases;
   - delegated action invocation;
   - redirect/toast, Inertia, JSON, database, notification, token, or other side-effect assertions.
5. Keep only cases the live controller contract can reach, but do not remove controller entry-point tests only because action integration tests already cover internal guards.

Load [`entrypoint-contracts.md`](entrypoint-contracts.md) for the complete
entry-point ownership rules before adding, deleting, or collapsing controller
cases.


### Observed Order

Use this global file order when a resource controller exposes all actions:

1. `create`
2. `destroy`
3. `edit`
4. `index`
5. `show`
6. `store`
7. `update`

Inside each action block, use failure-to-success order:

1. unauthenticated request;
2. authorization/current-`Workspace` `403`;
3. route-model binding and scoped ownership `404` from outer ancestor to leaf;
4. soft-deleted ancestor or leaf `404`;
5. lifecycle/state guard if it stops the request before validation or the action;
6. validation datasets and named validation cases;
7. delegated action invocation;
8. primary success response;
9. extra success/list variants.

Invokable controllers may stay flat when equivalent live invokable files are
flat, but the same order still applies.

When restoring or adding controller entry-point tests, place each case in this action and layer order immediately. Do not append restored cases at the end of the file, group them by implementation change, or move them out of the route/action block they prove.

## Related References

- [Parent router](README.md)
