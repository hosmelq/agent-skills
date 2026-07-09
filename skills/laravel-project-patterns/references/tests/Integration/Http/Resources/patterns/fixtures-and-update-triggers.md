# Resource Fixtures And Update Triggers

## When To Use

Use this leaf for resource fixture design and update triggers.

## Pattern

### Fixture Rules

- Use factory defaults for pass-through fields. Set explicit values only for resource-owned transformations or branches.
- Use coherent related models when the resource reads nested relationships.


### Update Triggers

Update this path whenever a resource adds, removes, renames, reorders, reformats, conditionally hides, or nests fields.

## Related References

- [Parent router](../README.md)
