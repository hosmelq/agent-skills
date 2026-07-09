# Factory Graph Coherence

## When To Use

Use when a persisted model belongs to a `Workspace` through multiple relations.

## Pattern

Factories and tests must keep the ownership graph coherent. Do not mix a child
from one `Workspace` with a parent from another unless deliberately asserting a
`404` or `403` elsewhere.

## Related References

- [Parent router](../README.md)
