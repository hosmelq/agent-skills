# Persisted Public ID Concern

## When To Use

Use this leaf for public ID creation and finder behavior.

## Pattern

### Public Id Concern Pattern

For public-id concerns, cover:

- a public id is generated when creating a model;
- `findByPublicId()` returns the correct record;
- `findOrFailByPublicId()` throws a model-not-found exception for an unknown public id.

Do not add a standalone `wherePublicId()` scope test when the finder tests already cover the same public ID constraint. Test the scope directly only if it gains behavior distinct from the finder contract.

Use the test support model instead of an application model when the behavior is generic.

```php
it('sets public id when creating', function (): void {
    $this->mock(ExampleIdClient::class, function (MockInterface $mock): void {
        $mock->shouldReceive('formattedId')
            ->once()
            ->with(ExampleAlphabet::Alphanumeric(), 10)
            ->andReturn('abc123de45');
    });

    $model = ExampleModel::query()->create();

    expect($model)
        ->public_id->toBeString()
        ->public_id->toBe('abc123de45');
});

it('finds assigned public ids case insensitively through the database', function (): void {
    $model = ExampleModel::query()->create(['public_id' => 'AbC123dE45']);

    expect($model->is(ExampleModel::findByPublicId('abc123de45')))->toBeTrue();
});

it('throws when model is not found by public id', function (): void {
    expect(fn () => ExampleModel::findOrFailByPublicId('missing'))
        ->toThrow(ModelNotFoundException::class);
});
```

## Related References

- [Parent router](../README.md)
