# Route Patterns for Controller Tests

## Purpose

This reference defines route-name, route-parameter, and scoped-binding conventions for controller feature tests.

## When To Use

Use this reference when a controller test calls `route(...)`, passes nested route parameters, or needs `403` versus `404` boundary coverage.

## Required Pattern

### Focused References

- [Route Selection And Binding Order](route-patterns/selection-and-binding.md): Use this leaf to identify the route surface, parameter keys, and ordered binding failures.
- [Settings Or Singleton Routes](route-patterns/settings-singleton.md): Use this leaf for a Workspace-bound settings or singleton route.
- [Two-Resource Route Chain](route-patterns/two-resource.md): Use this leaf for a Workspace plus member route chain.
- [Three-Resource Route Chain](route-patterns/three-resource.md): Use this leaf for a Workspace, parent, and child route chain.
- [Four-Resource Route Chain](route-patterns/four-resource.md): Use this leaf for a Workspace, parent, child, and leaf route chain.
- [Deeper Nested Route Chains](route-patterns/deeper-nesting.md): Use this leaf when a route nests beyond the four-resource example.

## Coverage Expectations

Route-pattern coverage is complete only when every route parameter has mismatch coverage for the behavior it can trigger. If a child table stores redundant ownership outside the direct parent FK, coverage is incomplete until that inconsistent graph is tested too.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable route-boundary coverage when adapting examples.
- Do not use real route, module, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](README.md)
