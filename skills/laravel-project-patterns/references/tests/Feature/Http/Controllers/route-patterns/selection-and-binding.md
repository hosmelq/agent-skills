# Route Selection And Binding Order

## When To Use

Use this leaf to identify the route surface, parameter keys, and ordered binding failures.

## Pattern

### Action Set

Controller tests in this style commonly cover these action groups when present:

- `create`
- `destroy`
- `edit`
- `index`
- `show`
- `store`
- `update`

The route file determines the active actions. Do not invent missing routes.


### Route Surface Shape

Authenticated web routes are normally scoped by `Workspace` and use `scopeBindings()` for nested chains. Encode the real route shape before writing a test.

| Shape                       | Route names                                                                                               | Parameter notes                                                                                        |
| --------------------------- | --------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------ |
| Settings or singleton route | `workspaces.settings.general`, `workspaces.update`                                                        | one `Workspace` route parameter when the route is `Workspace`-bound                                    |
| Two-resource chain          | `workspaces.parent-records.*`                                                                             | collection actions pass `workspace`; member actions pass `workspace`, `parent_record`                  |
| Three-resource chain        | `workspaces.parent-records.children.*`                                                                    | collection actions pass `workspace`, `parent_record`; member actions add `child_record`                |
| Four-resource chain         | `workspaces.parent-records.children.leaves.*`                                                             | collection actions pass `workspace`, `parent_record`, `child_record`; member actions add `leaf_record` |
| Invokable nested action     | `workspaces.parent-records.children.make-default`                                                         | pass every ancestor plus the child/leaf target                                                         |
| JSON API route              | `api.sessions.identity.login`, `api.sessions.code.request`, `api.sessions.code.login`, `api.profile.show` | use JSON helpers and the route's auth middleware contract                                              |


### Binding Order

For collection actions under a deep chain, use this order:

1. guest/auth failure;
2. authorized actor missing `Workspace` access -> `403`;
3. parent belongs to another Workspace -> `404`;
4. parent is soft deleted -> `404`;
5. child belongs to another parent in the same Workspace -> `404`;
6. child belongs to another Workspace -> `404`;
7. child is soft deleted -> `404`;
8. lifecycle/state guard if create/list/update is blocked or allowed by contract;
9. validation or success/list assertions.

For member actions under a deep chain, append leaf checks:

1. leaf belongs to another direct parent -> `404`;
2. leaf belongs to another ancestor graph in the same Workspace -> `404`;
3. leaf belongs to another Workspace -> `404`;
4. leaf is soft deleted -> `404`;
5. lifecycle/state guard if the member action is blocked or allowed by contract.


### Route Parameter Keys

Always derive parameter keys from the real route definition. The snippets below use synthetic keys:

- `workspace`
- `parent_record`
- `child_record`
- `leaf_record`

When `scopeBindings()` is active, mismatched chains are binding failures and should be asserted as `404`, not policy denials.

## Related References

- [Parent router](../route-patterns.md)
