# resources/js

## Purpose

This reference merges the authored Inertia React patterns under
`resources/js/**`: application bootstrap, providers/layouts, typed Wayfinder
forms, dependent reloads, conditional numeric fields, list/detail interactions,
shared contracts, and CSS entrypoints.

## When To Use

Use it before changing `resources/js`. Read the live page, its controller,
resource types, generated Wayfinder route/action imports, and pages with the
same state and interaction contract first.

Generated action/route/enum modules are consumers of the project generation
step. Do not hand-edit or copy generated output into authored examples.

## Required Pattern

### Reference Map

- [`patterns/application-bootstrap.md`](patterns/application-bootstrap.md): Application Bootstrap.
- [`patterns/layout-composition.md`](patterns/layout-composition.md): Layout Composition.
- [`patterns/typed-server-error-bridge.md`](patterns/typed-server-error-bridge.md): Typed Server Error Bridge.
- [`patterns/create-edit-shell-and-shared-form.md`](patterns/create-edit-shell-and-shared-form.md): Create/Edit Shell and Shared Form.
- [`patterns/dependent-selects-and-partial-reloads.md`](patterns/dependent-selects-and-partial-reloads.md): Dependent Selects and Partial Reloads.
- [`patterns/conditional-and-numeric-fields.md`](patterns/conditional-and-numeric-fields.md): Conditional and Numeric Fields.
- [`patterns/index-pages.md`](patterns/index-pages.md): Index Pages.
- [`patterns/ordered-list-movement.md`](patterns/ordered-list-movement.md): Route-bound movement within an ordered list.
- [`patterns/show-and-lifecycle-pages.md`](patterns/show-and-lifecycle-pages.md): Show and Lifecycle Pages.
- [`patterns/authored-typescript-contracts.md`](patterns/authored-typescript-contracts.md): Authored TypeScript Contracts.
- [`patterns/css-entrypoint.md`](patterns/css-entrypoint.md): CSS Entrypoint.

## Coverage Expectations

Backend feature tests cover component names, props, redirects, and flash/toast
contracts. Add browser tests only when a real browser suite exists and the
change requires interaction, focus, keyboard, or visual-runtime proof.

Run generated-type commands before TypeScript checks and the frontend build.

## Do Not

- Do not hand-edit generated Wayfinder or enum modules.
- Do not replace named-route generation with URL strings.
- Do not rely on the UI to enforce a backend invariant.
- Do not leave stale dependent selections after the parent changes.
- Do not leave pending state set after success or failure.
- Do not turn Inertia response assertions into claims of browser interaction.

## Related References

- [`references/project/configuration-and-tooling.md`](../../project/configuration-and-tooling.md)
- [`references/app/Http/Controllers/README.md`](../../app/Http/Controllers/README.md)
- [`references/app/Http/Resources/README.md`](../../app/Http/Resources/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../tests/Feature/Http/Controllers/README.md)
