# Code And Schema Contracts

## When To Use

Use this leaf for repository-safe edits, PHP class shape, Laravel generators,
migrations, and normal Eloquent attribute writes.

## Pattern

- Preserve concurrent changes. Re-read a file immediately before patching it.
- Keep new code aligned to the existing architecture. Do not add base folders, dependencies, or broad abstractions unless the task explicitly needs them.
- Preserve finality on existing classes unless the task explicitly changes that contract. For new classes, match the exact role's live sibling family: Data input objects and explicitly self-contained test helpers are `final`; actions, controllers, requests, resources, models, policies, providers, exceptions, and ordinary support classes are non-final. Never remove `final` from an Input or test helper merely because another application class family is non-final.
- Use `php artisan make:* --no-interaction` for Laravel-created files when practical, then rewrite generated output to match local patterns.
- Do not add database foreign key constraints when the repository uses schema-planning tools or application-level relationships instead. Use indexed `foreignId` columns only.
- Do not add migration `down()` methods when existing migrations intentionally omit them.
- Do not add `$fillable` or `$guarded` on models when the app globally calls `Model::unguard()`.
- Because models are globally unguarded, use `$model->update([...])` for normal persisted attribute mutations in app-owned code. Do not use `forceFill(...)->save()` as a mass-assignment workaround.

## Related References

- [`references/app/README.md`](../app/README.md)
- [`references/app/Models/README.md`](../app/Models/README.md)
- [`references/database/README.md`](../database/README.md)
