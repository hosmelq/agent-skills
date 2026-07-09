# Data Inputs

## When To Use

Read this focused reference when the task involves data inputs.

## Pattern

### Data Inputs

When using Spatie Laravel Data action inputs, place them close to the action namespace, such as `app/Actions/<Domain>/Inputs/<Verb><Model>Input.php`.

Laravel Data action inputs should extend `Spatie\LaravelData\Data`, use constructor-promoted readonly properties, and use `#[MapName(SnakeCaseMapper::class)]` when the HTTP/request payload is snake_case but PHP properties are camelCase.

Keep Data input constructor types aligned with the Form Request contract that creates them. Nullable request fields become nullable input properties; optional/omitted request fields become `Optional` unions. Do not add `Optional` just because a field is nullable.

Use `Optional` for omitted fields on partial updates. On create inputs, use `Optional` only for fields the request may omit and that should let the model `$attributes` defaults apply instead of forcing the default in the action.

Write Eloquent persistence with `$input->transform()` when the action needs mapped output names and omitted `Optional` fields excluded from the array. Prefer `transform()` over `toArray()` in action persistence code because it states that the input is being transformed for output before the Eloquent write. Do not manually duplicate model defaults inside the action when the model already owns them.

```php
#[MapName(SnakeCaseMapper::class)]
final class UpdateParentRecordInput extends Data
{
    public function __construct(
        public readonly null|Optional|string $description,
        public readonly Optional|string $name,
        public readonly bool|Optional $enabled,
    ) {
    }
}
```

For half-open interval updates, omitted endpoints fall back to persisted
values, explicit `null` remains meaningful, and the current row is excluded:

```php
private function ensureRangeIsAvailable(
    LeafRecord $leafRecord,
    UpdateLeafRecordInput $input,
): void {
    $minimumAmount = $input->minimumAmount instanceof Optional
        ? $leafRecord->minimum_amount
        : $input->minimumAmount;
    $maximumAmount = $input->maximumAmount instanceof Optional
        ? $leafRecord->maximum_amount
        : $input->maximumAmount;

    $overlapExists = LeafRecord::query()
        ->where('child_record_id', $leafRecord->child_record_id)
        ->whereKeyNot($leafRecord->getKey())
        ->whereRaw(
            "numrange(minimum_amount, maximum_amount, '[)') && numrange(?, ?, '[)')",
            [$minimumAmount, $maximumAmount],
        )
        ->exists();

    throw_if($overlapExists, LeafRecordRangeUnavailable::make());
}
```

Create checks the submitted endpoints. Update checks the effective persisted
plus submitted endpoints. Both allow adjacency, permit only one overlapping
open-ended terminal range, respect soft-delete and owner scope, and retain the
database exclusion constraint as the concurrency-safe authority.

## Related References

- [`../README.md`](../README.md)
