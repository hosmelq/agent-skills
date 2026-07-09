# Class-Local Model Contracts

## When To Use

Use this leaf for class-local model shape, casts, traits, defaults, and pure helpers.

## Pattern

### What Belongs Here

- Trait presence such as public-id or soft-delete traits.
- Trait presence for lifecycle concerns such as `HasDeactivation`; persisted transition behavior belongs in `tests/Integration/Models/Concerns`.
- Cast behavior using `new Model([...])`.
- Model defaults from `$attributes`.
- Pure accessors such as display names.
- Simple methods that can be tested with in-memory models or config fakes.
- Prunable query construction when the assertion can be focused and fast. Existing baseline tests may create minimal records to prove the prunable query result; broader persistence behavior belongs in `tests/Integration/Models`.
- Pure normalization helpers, such as code normalization, when no database state is needed.
- Cast shape for enums, immutable timestamps, arrays, floats, and value objects such as phone numbers.


### File Shape

- Import only the model, enums, traits, casts, and framework utilities needed.
- Use one `it(...)` per behavior.
- Keep names precise: `it('uses SoftDeletes trait')`, `it('correctly casts attributes')`, `it('sets model defaults')`.

Use this order, omitting only scenarios the model does not own:

| Order | Class-local scenario |
| ---: | --- |
| 1 | exact trait presence |
| 2 | in-memory casts |
| 3 | model defaults |
| 4 | accessor fallback priority |
| 5 | pure-normalization dataset, then blank result |
| 6 | focused prunable selection |
| 7 | config-driven predicate |


### Cast Tests

Use raw assigned values and assert the hydrated cast result:

```php
$model = new SomeModel([
    'created_at' => '2026-03-30 15:33:00',
    'amount' => 1.2,
    'status' => 'active',
]);

expect($model)
    ->created_at->toBeInstanceOf(CarbonImmutable::class)
    ->amount->toBe('1.20')
    ->status->toBe(Status::Active);
```

Decimal casts are strings. Date casts should be immutable where the app config makes dates immutable.
Float casts should assert `toBeFloat()` rather than an exact database-formatted decimal. Phone number casts can assert the value object type here; formatting belongs in resource or controller persistence tests.
Once an accessor or cast is covered here, a resource that only passes it through should use model-backed expectations; test any resource-owned transformation separately.


### Trait Tests

Use `class_uses_recursive(Model::class)` and assert the exact trait class.
Do not duplicate the reusable concern's method and scope behavior in every consuming model test.

```php
it('uses the public id trait', function (): void {
    expect(class_uses_recursive(ParentRecord::class))
        ->toContain(HasPublicId::class);
});
```


### Defaults and Pure Helpers

```php
it('sets model defaults', function (): void {
    $model = new ParentRecord();

    expect($model)
        ->enabled->toBeFalse()
        ->status->toBe(ParentRecordStatus::Draft);
});

it('uses the first available display name', function (): void {
    $primaryActor = new Actor([
        'name' => 'Primary',
        'secondary_label' => 'Fallback',
    ]);
    $fallbackActor = new Actor(['secondary_label' => 'Fallback']);
    $anonymousActor = new Actor();

    expect($primaryActor->display_name)->toBe('Primary')
        ->and($fallbackActor->display_name)->toBe('Fallback')
        ->and($anonymousActor->display_name)->toBe('');
});

it('normalizes contact numbers', function (string $value, string $expected): void {
    expect(Actor::normalizeContactNumber($value))->toBe($expected);
})->with([
    'formatted' => ['+1 (555) 010-0200', '15550100200'],
    'already normalized' => ['15550100200', '15550100200'],
]);

it('returns an empty normalized contact number for blank input', function (): void {
    expect(Actor::normalizeContactNumber(''))->toBe('');
});
```

## Related References

- [Parent router](../README.md)
