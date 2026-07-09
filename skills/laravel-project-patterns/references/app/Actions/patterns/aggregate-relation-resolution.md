# Aggregate Relation Resolution

## When To Use

Read this leaf only when one aggregate action accepts several optional related
public IDs whose compatibility depends on their effective persisted and
submitted values.

## Pattern

Resolve every submitted public ID inside the aggregate owner. Treat a selected
relation as a lookup suggestion, not confirmation of its optional companions:
each submitted relation must be independently resolved and validated.

On partial update, `Optional` means retain the persisted value; explicit
`null` means clear it. Load the persisted relation with `withTrashed()` when a
historical current assignment remains valid but the same unavailable record
cannot be newly assigned.

```php
private function effectiveString(
    null|string $persistedValue,
    null|Optional|string $inputValue,
): null|string {
    return $inputValue instanceof Optional ? $persistedValue : $inputValue;
}

private function currentMethod(Shipment $shipment): null|DeliveryMethod
{
    return $shipment->deliveryMethod()->withTrashed()->first();
}

private function ensureRelationsAreCompatible(
    null|Recipient $recipient,
    null|Locker $locker,
    null|DeliveryMethod $deliveryMethod,
    null|DeliveryRate $deliveryRate,
): void {
    if ($locker instanceof Locker
        && $recipient instanceof Recipient
        && $locker->recipient_id !== $recipient->id) {
        throw CannotUpdateShipment::becauseLockerRecipientDoesNotMatch();
    }

    if ($deliveryRate instanceof DeliveryRate
        && ! $deliveryMethod instanceof DeliveryMethod) {
        throw CannotUpdateShipment::becauseRateRequiresMethod();
    }

    if ($deliveryRate instanceof DeliveryRate
        && $deliveryRate->delivery_method_id !== $deliveryMethod->id) {
        throw CannotUpdateShipment::becauseMethodDoesNotMatchRate();
    }
}
```

Run paired-field and measurement validation against effective values, not only
the submitted fragment. Do not infer a lock or replacement query strategy from
this relation-resolution pattern.

## Related References

- [`../README.md`](../README.md)
- [`../../../tests/Integration/Actions/scenario-catalog/aggregate-update-actions.md`](../../../tests/Integration/Actions/scenario-catalog/aggregate-update-actions.md)
- [`../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/relation-confirmation-and-continuity.md`](../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/relation-confirmation-and-continuity.md)
