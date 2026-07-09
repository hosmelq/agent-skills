# Reference-Data Static Cache

## When To Use

Use only for immutable reference-data lookups in a resource.

## Pattern

```php
/**
 * @var array<string, ReferenceRegion>
 */
private static array $regionsByCode = [];

private function resolveRegion(RegionCode $regionCode): ReferenceRegion
{
    if (! array_key_exists($regionCode->value, self::$regionsByCode)) {
        self::$regionsByCode[$regionCode->value] = ReferenceRegion::query()
            ->where('code', $regionCode)
            ->firstOrFail();
    }

    return self::$regionsByCode[$regionCode->value];
}
```

Long-running workers keep static state between requests. Key a resource cache
by immutable reference data only; never cache request-, actor-, or
`Workspace`-specific values or model instances whose authorization context can
change.

## Related References

- [Parent router](../README.md)
