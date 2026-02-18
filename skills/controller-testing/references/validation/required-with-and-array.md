# `required_with` and `array` Validation Snippets

Use these when request rules include `required_with` or `array`.

## Store Example (`organizations.locations.store`)

```php
it('validates fields', function (array $data, array $expected): void {
    $organization = Organization::factory()->createOne();

    login(organization: $organization);

    $response = post(route('organizations.locations.store', [
        'organization' => $organization,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'array' => [
        'data' => [
            'opening_hours' => 'a',
        ],
        'expected' => [
            'opening_hours' => 'The opening hours field must be an array.',
        ],
    ],
    'required_with:latitude' => [
        'data' => [
            'latitude' => 1,
        ],
        'expected' => [
            'longitude' => 'The longitude field is required when latitude is present.',
        ],
    ],
    'required_with:longitude' => [
        'data' => [
            'longitude' => 1,
        ],
        'expected' => [
            'latitude' => 'The latitude field is required when longitude is present.',
        ],
    ],
]);
```

## Update Example (`organizations.locations.update`)

```php
it('validates fields', function (array $data, array $expected): void {
    $location = Location::factory()->createOne();

    login(organization: $location->organization);

    $response = patch(route('organizations.locations.update', [
        'organization' => $location->organization,
        'location' => $location,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'array' => [
        'data' => [
            'opening_hours' => 'a',
        ],
        'expected' => [
            'opening_hours' => 'The opening hours field must be an array.',
        ],
    ],
    'required_with:latitude' => [
        'data' => [
            'latitude' => 1,
        ],
        'expected' => [
            'longitude' => 'The longitude field is required when latitude is present.',
        ],
    ],
    'required_with:longitude' => [
        'data' => [
            'longitude' => 1,
        ],
        'expected' => [
            'latitude' => 'The latitude field is required when longitude is present.',
        ],
    ],
]);
```
