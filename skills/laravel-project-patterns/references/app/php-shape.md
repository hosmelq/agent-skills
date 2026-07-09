# Application PHP Shape

## When To Use

Use this leaf when creating or reviewing a PHP file anywhere under `app/**`
before loading the domain-specific router.

## Pattern

- Every PHP file in the application uses `declare(strict_types=1);`.
- Prefer explicit imports, explicit return types, and typed parameters.
- Always use curly braces for control structures, even when the body has one statement.
- Prefer constructor property promotion for injected dependencies and promoted values. Do not keep empty zero-argument constructors unless the constructor is private.
- Use `#[Override]` when overriding framework methods or properties where the local code already does.
- Use PHPDoc blocks for array shapes, Eloquent relationship generics, resource model properties, and exception annotations.
- Keep enum cases in TitleCase and use local enum traits/attributes when sibling enums do.
- Prefer small methods with descriptive names over inline notes.

```php
<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\CannotGenerateExampleValue;
use App\Models\Workspace;
use ExamplePackage\Client;

class GenerateExampleValue
{
    public const int MAX_RETRY_ATTEMPTS = 20;

    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @return array{code: string, attempts: int}
     */
    public function handle(Workspace $workspace): array
    {
        $attempts = 0;

        while ($attempts < self::MAX_RETRY_ATTEMPTS) {
            $code = $this->client->formattedId();

            ++$attempts;

            if (! $this->codeExists($workspace, $code)) {
                return [
                    'attempts' => $attempts,
                    'code' => $code,
                ];
            }
        }

        throw CannotGenerateExampleValue::maxAttempts($attempts);
    }

    private function codeExists(Workspace $workspace, string $code): bool
    {
        return $workspace->exampleRecords()
            ->where('normalized_code', $code)
            ->exists();
    }
}
```

## Related References

- [`references/app/README.md`](README.md)
- [`references/core/code-and-schema.md`](../core/code-and-schema.md)
