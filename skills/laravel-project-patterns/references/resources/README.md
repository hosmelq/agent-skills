# Resource-Layer Patterns

## Purpose

Route frontend, Blade-shell, and React Email work to the matching resource
reference.

## When To Use

Use this router for files under `resources/js/**`, `resources/views/**`, or
`resources/react-email/**`.

## Required Pattern

- Load [`js/README.md`](js/README.md) for Inertia React, Wayfinder, forms,
  layouts, TypeScript contracts, and CSS.
- Load [`views/README.md`](views/README.md) for the Inertia root Blade shell and
  hand-authored application views.
- Load [`react-email/README.md`](react-email/README.md) for React Email sources,
  export lifecycle, generated Blade output, and mail assets.

## Coverage Expectations

Select only the branch matching the changed path and preserve its generated
versus hand-authored boundary.

## Do Not

- Do not load sibling resource branches without a touched dependency.
- Do not edit generated mail output as though it were source.

## Related References

- [`references/MAP.md`](../MAP.md)
- [`references/project/README.md`](../project/README.md)
