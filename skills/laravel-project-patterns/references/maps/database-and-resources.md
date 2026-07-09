# Database And Resource Map

## When To Use

Use this map to select one database or resource router.

## Pattern

- [`database/README.md`](../database/README.md): select migrations or factories.
  - [`database/migrations/README.md`](../database/migrations/README.md) and
    `database/migrations/patterns/*.md`: schema-focused routing and leaves.
  - [`database/factories/README.md`](../database/factories/README.md) and
    `database/factories/patterns/*.md`: factory routing and leaves.
- [`resources/README.md`](../resources/README.md): select JavaScript, Blade, or
  React Email.
  - [`resources/js/README.md`](../resources/js/README.md) and
    `resources/js/patterns/*.md`: Inertia React, Wayfinder, forms, layouts,
    TypeScript, and CSS.
  - [`resources/views/README.md`](../resources/views/README.md): Blade shells and
    hand-authored views.
  - [`resources/react-email/README.md`](../resources/react-email/README.md): React
    Email source/export lifecycle and mail assets.

## Related References

- [Parent router](../MAP.md)
