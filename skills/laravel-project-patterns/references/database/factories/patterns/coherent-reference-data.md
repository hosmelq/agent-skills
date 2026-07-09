# Coherent Reference Data

## When To Use

Read this focused reference when the task involves coherent reference data.

## Pattern

### Coherent Reference Data

When several factories need a country/region/city/phone combination, put the
coherent generator in a reusable factory concern. Return a documented shape
and let a deterministic state spread the complete related values:

```php
<?php

declare(strict_types=1);

namespace Database\Factories\Concerns;

use App\Enums\RegionCode;
use App\Models\Reference\Locality;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;

trait GeneratesRegionData
{
    /**
     * @return array{city: string, region_code: RegionCode, subdivision_code: null|string}
     */
    protected function generateRegionData(null|RegionCode $regionCode = null): array
    {
        $regionCode ??= fake()->randomElement(RegionCode::cases());

        assert($regionCode instanceof RegionCode);

        $locality = Locality::query()
            ->where('region_code', $regionCode)
            ->inRandomOrder()
            ->firstOrFail();

        return [
            'city' => $locality->name,
            'region_code' => $regionCode,
            'subdivision_code' => $locality->subdivision->code,
        ];
    }

    protected function generateContactNumber(RegionCode $regionCode): string
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();
        $example = $phoneNumberUtil->getExampleNumberForType(
            $regionCode->value,
            PhoneNumberType::MOBILE,
        );

        assert($example instanceof PhoneNumber);

        return sprintf(
            '+%s %s',
            $example->getCountryCode(),
            $example->getNationalNumber(),
        );
    }
}
```

```php
public function withRegionCode(RegionCode $regionCode): static
{
    return $this->state(fn (): array => [
        ...$this->generateRegionData($regionCode),
        'contact_number' => $this->generateContactNumber($regionCode),
    ]);
}
```

Do not independently fake fields whose validity depends on the selected
reference row. The concern may query immutable reference data; ordinary domain
factories should remain independent of mutable application services.

## Related References

- [`../README.md`](../README.md)
