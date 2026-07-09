# Deeper Nested Route Chains

## When To Use

Use this leaf when a route nests beyond the four-resource example.

## Pattern

### N-Level Nested Resource Rule

If routes nest deeper than the examples:

1. pass every ancestor parameter in route order;
2. add mismatch tests for every ancestor, direct parent, and leaf;
3. add soft-delete checks for every model using soft deletes;
4. add same-`Workspace` wrong-parent graph checks when those records can exist;
5. add redundant ownership mismatch checks when a child stores both a direct-parent FK and a `Workspace`/ancestor FK.

Extend the four-resource example above by inserting each additional ancestor
in both the route name and the ordered parameter array.

## Related References

- [Parent router](../route-patterns.md)
