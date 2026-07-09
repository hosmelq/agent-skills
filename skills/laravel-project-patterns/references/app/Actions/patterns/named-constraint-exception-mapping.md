# Named Constraint Exception Mapping

## When To Use

Read this leaf when the live action already performs an application precheck
for a database-backed invariant and must map the named constraint race to the
same domain exception.

## Pattern

Keep the readable precheck, rely on the database constraint for concurrency,
and translate only the explicitly named constraint. Rethrow every unrelated
query failure:

```php
try {
    return $workspace->shipments()->create($values);
} catch (QueryException $exception) {
    if (str_contains(
        $exception->getMessage(),
        'shipments_active_reference_unique',
    )) {
        throw CannotCreateShipment::becauseReferenceAlreadyExists();
    }

    throw $exception;
}
```

The integration suite must cover the ordinary precheck, the named-constraint
race mapping, and propagation of an unrelated query exception when those
branches exist. Do not convert arbitrary `QueryException` failures into a
validation message and do not add a lock when the named constraint already
owns the invariant.

## Related References

- [`../README.md`](../README.md)
- [`../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/named-constraint-race-mapping.md`](../../../tests/Integration/Actions/scenario-catalog/aggregate-actions/named-constraint-race-mapping.md)
