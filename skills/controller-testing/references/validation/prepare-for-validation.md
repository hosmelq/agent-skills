# `prepareForValidation()` Validation Snippets

Use these when a request mutates incoming data before rule evaluation.

## Public ID Coercion Before Scoped `exists` (`organizations.customers.lockers.store`)

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

```php
it('creates a locker with a shipping method public id', function (): void {
    $customer = Customer::factory()->createOne();
    $shippingMethod = ShippingMethod::factory()->for($customer->organization)->createOne();

    login(organization: $customer->organization);

    $response = post(route('organizations.customers.lockers.store', [
        'organization' => $customer->organization,
        'customer' => $customer,
    ]), [
        'shipping_method_id' => $shippingMethod->public_id,
    ]);

    $locker = Locker::query()
        ->where('customer_id', $customer->id)
        ->where('shipping_method_id', $shippingMethod->id)
        ->sole();

    $response->assertRedirectToRoute('organizations.customers.lockers.show', [
        'organization' => $customer->organization,
        'customer' => $customer->public_id,
        'locker' => $locker->public_id,
    ]);

    assertDatabaseHas(Locker::class, [
        'customer_id' => $customer->id,
        'shipping_method_id' => $shippingMethod->id,
    ]);
});
```

## Merge Route Model Value When Field Is Blank (`organizations.customers.addresses.update`)

```php
it('updates when country code is empty but matches the existing address', function (): void {
    $address = CustomerAddress::factory()->createOne([
        'country_code' => CountryCode::Nicaragua,
    ]);

    login(organization: $address->customer->organization);

    $response = patch(route('organizations.customers.addresses.update', [
        'organization' => $address->customer->organization,
        'customer' => $address->customer,
        'address' => $address,
    ]), [
        'country_code' => '',
        'province_code' => 'MN',
    ]);

    $response->assertRedirectToRoute('organizations.customers.addresses.index', [
        'organization' => $address->customer->organization,
        'customer' => $address->customer,
    ])
        ->assertToast('Address updated', 'The address was updated successfully.');

    $address->refresh();

    expect($address)
        ->country_code->toBe(CountryCode::Nicaragua)
        ->province_code->toBe('MN');
});
```

## Stored-Bound Cross-Field Validation (`update`)

```php
it('validates minimum estimated transit time against the stored maximum', function (): void {
    $shippingMethod = ShippingMethod::factory()->createOne([
        'maximum_estimated_transit_time' => 5,
        'minimum_estimated_transit_time' => 3,
    ]);

    login(organization: $shippingMethod->organization);

    $response = patch(route('organizations.shipping-methods.update', [
        'organization' => $shippingMethod->organization,
        'shipping_method' => $shippingMethod,
    ]), [
        'minimum_estimated_transit_time' => 6,
    ]);

    $response->assertRedirectBackWithErrors([
        'minimum_estimated_transit_time' => 'The minimum estimated transit time field must be less than or equal to 5.',
    ]);
});
```

```php
it('validates maximum weight against the stored minimum weight', function (): void {
    $rate = ShippingMethodRate::factory()->createOne([
        'maximum_weight' => 15,
        'minimum_weight' => 10,
    ]);

    login(organization: $rate->shippingMethod->organization);

    $response = patch(route('organizations.shipping-methods.rates.update', [
        'organization' => $rate->shippingMethod->organization,
        'shipping_method' => $rate->shippingMethod,
        'rate' => $rate,
    ]), [
        'maximum_weight' => 5,
    ]);

    $response->assertRedirectBackWithErrors([
        'maximum_weight' => 'The maximum weight field must be greater than or equal to 10.0000.',
    ]);
});
```
