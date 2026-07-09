# Factory State Methods

## When To Use

Use this leaf for typed, meaningful factory state examples.

## Pattern

```php
public function forRegion(RegionCode $regionCode): static
{
    return $this->state(['region_code' => $regionCode]);
}

public function openEnded(): static
{
    return $this->state(['maximum_amount' => null]);
}

public function roundUp(): static
{
    return $this->state([
        'rounding_increment' => 1,
        'rounding_mode' => RoundingMode::Up,
    ]);
}
```

## Related References

- [Parent router](../core-rules.md)
