# Models And Policies

## When To Use

Use this leaf for shared model and policy boundaries before selecting their focused routers.

## Pattern

### Models And Policies

- Models rely on global unguarding; do not add `$fillable` or `$guarded` when siblings omit them.
- For normal app-owned persisted attribute mutations, use `$model->update([...])`. Do not use `forceFill(...)->save()` just to bypass mass assignment because models are globally unguarded during application boot.
- Use typed relationship methods with Eloquent generic return PHPDoc.
- Public-id route keys are provided by the local trait unless a model overrides `getRouteKeyName()` for a different public key such as a slug.
- Public ID finder helpers return models and throw framework 404s. Use them at controller boundaries when converting validated public IDs to persisted internal IDs.
- Soft-deletable models should have unit trait/cast coverage and integration tests only for project/system behavior that depends on saved records.
- `Workspace` authorization is usually expressed through actor ownership or membership checks on the relevant `Workspace` model.
- Policies are commonly validated through controller feature tests instead of standalone policy tests, unless the policy itself has isolated logic worth testing.

## Related References

- [Parent router](../README.md)
