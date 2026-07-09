# Framework Contract Actions

## When To Use

Read this focused reference when the task involves framework contract actions.

## Pattern

### Framework Contract Actions

When an action implements a framework contract, call the contract method directly instead of forcing a `handle(...)` shape. Cover validation exceptions, validation bags, notifications, and persistence only when that contract action owns them:

```php
it('validates actor profile fields', function (array $data, array $expected): void {
    $actor = Actor::factory()->createOne();

    expect(fn () => resolve(UpdateActorProfileInformation::class)->update($actor, $data))
        ->toThrow(function (ValidationException $exception) use ($expected): void {
            expect($exception->validator->errors()->messages())->toBe($expected);
        });
})->with([
    'required' => [
        'data' => [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
        ],
        'expected' => [
            'email' => ['The email field is required.'],
            'first_name' => ['The first name field is required.'],
            'last_name' => ['The last name field is required.'],
        ],
    ],
]);
```

## Related References

- [`../README.md`](../README.md)
