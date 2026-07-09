# Credential Verification Variants

## When To Use

Read this reference when a provider controller verifies credentials with an
SDK or validates a signed token directly.

## Pattern

### Credential Verification Variants

The current project keeps both mechanisms inside provider-specific
controllers; it does not define verifier service classes.

SDK-backed controllers configure the client, verify the submitted token, and
map a false result to the stable request field:

```php
$client->setClientId(Config::string('services.identity.client_id'));

/** @var array{email: string, sub: string}|false $claims */
$claims = $client->verifyIdToken(
    (string) $request->string('id_token'),
);

throw_if($claims === false, ValidationException::withMessages([
    'id_token' => __('auth.providers.identity.invalid_credentials'),
]));
```

Signed-token controllers cache and parse only the provider's public key set:

```php
/**
 * @return array<string, Key>
 */
private function retrieveKeySet(): array
{
    /** @var array<mixed> $keys */
    $keys = Cache::remember(
        'identity-keys',
        now()->addMinutes(5),
        function (): array {
            /** @var array<mixed> */
            return Http::retry(3, 200)
                ->timeout(5)
                ->get(Config::string('services.identity.keys_url'))
                ->throw()
                ->json();
        },
    );

    return JWK::parseKeySet($keys);
}
```

Decode only through the verified key set, catch verification failures, then
validate audience, expiry, issuer, and nonce before using any identity claims:

```php
try {
    /** @var array{aud: string, email?: string, exp: int, iss: string, nonce: string, sub: string} $claims */
    $claims = json_decode(json_encode(JWT::decode(
        (string) $request->string('id_token'),
        $this->retrieveKeySet(),
    )), true);
} catch (Throwable) {
    throw ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]);
}

throw_if(
    $claims['aud'] !== Config::string('services.identity.client_id'),
    ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]),
);

throw_if($claims['exp'] < now()->timestamp, ValidationException::withMessages([
    'id_token' => __('auth.providers.identity.invalid_credentials'),
]));

throw_if(
    $claims['iss'] !== Config::string('services.identity.issuer'),
    ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]),
);

throw_unless(
    hash_equals((string) $request->string('nonce'), $claims['nonce']),
    ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]),
);
```

Cache public keys only, never submitted credentials or nonces. Keep retry
count, delay, timeout, and cache duration explicit. Extract a verifier service
only if the live project intentionally adopts that boundary and its tests.

## Related References

- [`../external-identity-flow.md`](../external-identity-flow.md)
