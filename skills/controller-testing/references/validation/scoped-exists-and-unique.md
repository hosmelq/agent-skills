# Scoped `exists` and `unique` Validation Snippets

Use these when validation rules depend on the current parent scope (for example: organization-level constraints).

## Scoped `exists` Example (`organizations.customers.lockers.store`)

```php
it('validates shipping method belongs to organization', function (): void {
    $customer = Customer::factory()->createOne();

    $unrelatedShippingMethod = ShippingMethod::factory()->createOne();

    login(organization: $customer->organization);

    $response = post(route('organizations.customers.lockers.store', [
        'organization' => $customer->organization,
        'customer' => $customer,
    ]), [
        'shipping_method_id' => $unrelatedShippingMethod->public_id,
    ]);

    $response->assertRedirectBackWithErrors([
        'shipping_method_id' => 'The selected shipping method id is invalid.',
    ]);
});
```

```php
it('validates fields', function (array $data, array $expected): void {
    $customer = Customer::factory()->createOne();

    login(organization: $customer->organization);

    $response = post(route('organizations.customers.lockers.store', [
        'organization' => $customer->organization,
        'customer' => $customer,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'exists' => [
        'data' => [
            'shipping_method_id' => 'asdf',
        ],
        'expected' => [
            'shipping_method_id' => 'The selected shipping method id is invalid.',
        ],
    ],
]);
```

## Scoped `exists` with Parent-Dependent Filter (`organizations.locations.store`)

```php
it('validates province code exists for the selected country code', function (): void {
    $organization = Organization::factory()->createOne();

    login(organization: $organization);

    $response = post(route('organizations.locations.store', [
        'organization' => $organization,
    ]), [
        'country_code' => 'NI',
        'province_code' => 'XX',
    ]);

    $response->assertRedirectBackWithErrors([
        'province_code' => 'The selected province code is invalid.',
    ]);
});
```

## Scoped `unique` Example (`organizations.customers.store`)

```php
it('validates email uniqueness within the same organization', function (): void {
    $customer = Customer::factory()->createOne([
        'email' => 'john@gmail.com',
    ]);

    login(organization: $customer->organization);

    $response = post(route('organizations.customers.store', [
        'organization' => $customer->organization,
    ]), [
        'email' => 'john@gmail.com',
    ]);

    $response->assertRedirectBackWithErrors([
        'email' => 'The email has already been taken.',
    ]);
});
```

## Scoped `unique` on Update with `ignore(...)`

```php
it('validates email uniqueness within the same organization on update', function (): void {
    $customer1 = Customer::factory()->createOne([
        'email' => 'john@gmail.com',
    ]);

    $customer2 = Customer::factory()->recycle($customer1->organization)->createOne([
        'email' => 'jane@gmail.com',
    ]);

    login(organization: $customer1->organization);

    $response = patch(route('organizations.customers.update', [
        'organization' => $customer1->organization,
        'customer' => $customer2,
    ]), [
        'email' => 'john@gmail.com',
    ]);

    $response->assertRedirectBackWithErrors([
        'email' => 'The email has already been taken.',
    ]);
});
```
