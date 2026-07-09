# resources/react-email

## Purpose

Document the `resources/react-email` contract for React Email templates, export scripts, generated Blade views, and mail assets.

## When To Use

Use this reference before creating or changing React Email templates, static email assets, React Email scripts, or generated mail views/assets.

## Required Pattern

Select source/package and asset-path behavior separately from the export
lifecycle. React Email source owns generated Blade views and mail assets.

### Focused References

- [React Email Package And Assets](patterns/package-and-assets.md): Use this leaf for package scripts and preview/export asset paths.
- [React Email Export Script](patterns/export-script.md): Use this leaf for the complete generated-view and static-asset export script.

## Coverage Expectations

Load only the focused leaf for the changed source or export surface.

## Do Not

- Do not edit generated Blade or exported asset output as the source of truth.
- Do not load both leaves unless source/package and export behavior both change.

## Related References

- [`SKILL.md`](../../../SKILL.md)
- [`references/resources/views/README.md`](../views/README.md)
- [`references/app/Notifications/README.md`](../../app/Notifications/README.md)
- [`references/project/README.md`](../../project/README.md)
