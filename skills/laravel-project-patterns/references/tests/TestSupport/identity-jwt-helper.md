# Deterministic Identity JWT Helper

## When To Use

Use this leaf for test-only external identity input consumed by HTTP fakes or SDK mocks.

## Pattern

### Helper Rules

Helpers in this path should:

- create deterministic values that can be used with `Http::fake()` or mocked SDK clients;
- expose a small API that lets tests override identity claims or payload details;
- keep key fixtures, certificates, or binary fixtures scoped to tests only;
- avoid mutable global state that can leak between tests.

```php
<?php

declare(strict_types=1);

namespace Tests\TestSupport;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

final class ExampleIdentityJwt
{
    private const string KEY_ID = 'test-signing-key';

    private static null|string $privateKey = null;

    /**
     * @var null|array{rsa: array{e: string, n: string}}
     */
    private static null|array $publicKeyDetails = null;

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array{jwks: array{keys: array<int, array<string, string>>}, payload: array<string, mixed>, token: string}
     */
    public static function issueToken(array $overrides = []): array
    {
        $payload = array_merge([
            'aud' => Config::string('services.example.client_id'),
            'email' => 'actor@example.com',
            'exp' => now()->addHour()->timestamp,
            'iat' => now()->timestamp,
            'iss' => 'https://identity.example.test',
            'nonce' => 'nonce-value',
            'sub' => '123',
        ], $overrides);

        return [
            'jwks' => self::jwks(),
            'payload' => $payload,
            'token' => JWT::encode($payload, self::privateKey(), 'RS256', self::KEY_ID),
        ];
    }

    /**
     * @return array{keys: array<int, array<string, string>>}
     */
    public static function jwks(): array
    {
        $details = self::publicKeyDetails();

        return [
            'keys' => [[
                'alg' => 'RS256',
                'e' => self::base64UrlEncode($details['rsa']['e']),
                'kid' => self::KEY_ID,
                'kty' => 'RSA',
                'n' => self::base64UrlEncode($details['rsa']['n']),
                'use' => 'sig',
            ]],
        ];
    }

    private static function base64UrlEncode(string $value): string
    {
        return (string) Str::of($value)
            ->toBase64()
            ->swap(['+' => '-', '/' => '_'])
            ->rtrim('=');
    }

    private static function privateKey(): string
    {
        if (self::$privateKey !== null) {
            return self::$privateKey;
        }

        return self::$privateKey = file_get_contents(base_path('tests/TestSupport/example_signing_private.pem'));
    }

    /**
     * @return array{rsa: array{e: string, n: string}}
     */
    private static function publicKeyDetails(): array
    {
        if (self::$publicKeyDetails !== null) {
            return self::$publicKeyDetails;
        }

        return self::$publicKeyDetails = openssl_pkey_get_details(
            openssl_pkey_get_public(file_get_contents(base_path('tests/TestSupport/example_signing_public.pem')))
        );
    }
}
```

## Related References

- [Parent router](README.md)
