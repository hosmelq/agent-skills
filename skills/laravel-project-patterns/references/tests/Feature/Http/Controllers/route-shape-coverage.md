# Controller Route Shape Coverage

## When To Use

Use this leaf to select the applicable nested route shape and coverage depth.

## Pattern

### Route Shapes

Use `route-patterns.md` for route examples and parameter composition.

| Shape                       | Applies to                                        | Required coverage                                                                                                                  |
| --------------------------- | ------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| Settings or singleton route | one page or one mutation under `workspaces.*`     | auth, `403`, response/mutation contract                                                                                            |
| Two-resource route chain    | `workspaces.parent-records.*`                     | `Workspace` auth/authorization, leaf `Workspace` mismatch, soft-deleted leaf, page/list/mutation contract                          |
| Three-resource route chain  | `workspaces.parent-records.children.*`            | all two-resource checks plus parent mismatch, child direct-parent mismatch, child `Workspace` mismatch, soft-deleted parent/child  |
| Four-resource route chain   | `workspaces.parent-records.children.leaves.*`     | outer parent, middle child, and leaf boundaries, including same-`Workspace` wrong-parent graphs and redundant ownership mismatches |
| Invokable nested mutation   | `workspaces.parent-records.children.make-default` | auth, `403`, parent/child `404`, side effect                                                                                       |
| Public JSON endpoint        | public JSON session or access-code endpoints      | validation first, domain failures, success JSON and side effects                                                                   |
| Protected JSON endpoint     | authenticated API endpoint                        | guest `401`, authenticated success, public-ID JSON                                                                                 |

For delegated action mocks, Request-to-Input mapping, mapped domain
rejections, return-value rules, and the controller-test deduplication gate, load
[`delegated-action-contracts.md`](delegated-action-contracts.md).

## Related References

- [Parent router](README.md)
