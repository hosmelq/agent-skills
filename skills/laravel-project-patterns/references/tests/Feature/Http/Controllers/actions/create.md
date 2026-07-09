# Create Action Templates

## Purpose

This reference defines `describe('create')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes a `create` page. For JSON endpoints, keep the same boundary order and adapt assertions with `../modes/api-json.md`.

## Required Pattern

Use these templates as baselines and extend them with every boundary present in
the real route, policy, controller, model, and equivalent live siblings with
the same route shape and response contract.

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full collection binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, outer parent wrong Workspace, outer parent soft-deleted, child wrong parent in the same Workspace, child wrong Workspace, child soft-deleted, then lifecycle/create-page contract.

### Reference Map

- [`create/two-resource.md`](create/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.create`).
- [`create/three-resource.md`](create/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.create`).
- [`create/four-resource.md`](create/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.create`).

### Partial Reload Example

Use this when the page supports a dependent option reload. Keep the route context in the full response and assert only the refreshed prop in `reloadOnly(...)`.

```php
$response->assertOk()
    ->assertInertia(function (AssertableInertia $page) use ($workspace, $relatedRecord): void {
        $page->component('parent-records/Create')
            ->where('workspace.id', $workspace->public_id)
            ->where('relatedRecordId', $relatedRecord->public_id)
            ->reloadOnly('relatedOptions', function (AssertableInertia $reload) use ($relatedRecord): void {
                $reload->where('relatedOptions.0.value', $relatedRecord->public_id)
                    ->where('relatedOptions.0.label', $relatedRecord->name);
            });
    });
```

### System Create Patterns

- Create pages with enum or reference-data options assert those props in the primary page contract.
- Dynamic option pages assert `reloadOnly(...)` for the partial prop and the input that scopes it.
- Nested create pages assert every ancestor public ID prop used by the form.
- Deep create pages follow parent boundary order before lifecycle guards and the success response.

## Coverage Expectations

Use the live controller, routes, form requests, policies, resources, and
equivalent live siblings with the same route shape and response contract to
decide the complete action matrix. Preserve examples, but keep them synthetic
and only implement applicable cases in PHP.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
