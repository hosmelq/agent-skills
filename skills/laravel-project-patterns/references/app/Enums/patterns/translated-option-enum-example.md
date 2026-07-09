# Translated Option Enum Example

## When To Use

Read this focused reference when the task involves translated option enum example.

## Pattern

### Translated Option Enum Example

```php
<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\Options;
use App\Enums\MetaProperties\TranslationKey;
use ArchTech\Enums\Comparable;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string Active()
 * @method static string Archived()
 * @method static string Draft()
 */
enum ExampleStatus: string
{
    use Comparable;
    use InvokableCases;
    use Options;
    use Values;

    #[TranslationKey('example_status.active')]
    case Active = 'active';

    #[TranslationKey('example_status.archived')]
    case Archived = 'archived';

    #[TranslationKey('example_status.draft')]
    case Draft = 'draft';
}
```

Option concern and metadata property shape:

```php
<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use function App\__;

use App\Enums\MetaProperties\TranslationKey;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use BackedEnum;

/**
 * @method string translationKey()
 */
#[Meta(TranslationKey::class)]
trait Options
{
    use Metadata;

    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function options(): array
    {
        /** @var array<int, array{label: string, value: string}> */
        return collect(static::cases())->map(fn (BackedEnum $case): array => [
            'label' => __($case->translationKey()),
            'value' => $case->value,
        ])->all();
    }
}
```

Metadata property shape:

```php
<?php

declare(strict_types=1);

namespace App\Enums\MetaProperties;

use ArchTech\Enums\Meta\MetaProperty;
use Attribute;
use Override;

#[Attribute]
class TranslationKey extends MetaProperty
{
    #[Override]
    public static function method(): string
    {
        return 'translationKey';
    }
}
```

Use the smallest trait set that matches the enum's public contract:

| Contract | Trait set |
| --- | --- |
| exact backed values only | `Values` |
| static case access plus values | `InvokableCases`, `Values` |
| direct comparisons plus static access | `Comparable`, `InvokableCases`, `Values` |
| translated `{label, value}` options | `Comparable`, `InvokableCases`, `Options`, `Values` |
| pure enum static case names | `InvokableCases` with `@method static string CaseName()` |

Do not add traits only for symmetry. Each trait-owned method is a contract that
must remain covered by its consumer or enum unit tests.

## Related References

- [`../README.md`](../README.md)
