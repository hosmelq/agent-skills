# Verification Failure Mapping

## When To Use

Read this reference when a provider verifier exposes several credential failure modes.

## Pattern

### Verification Failure Mapping

Provider-specific verification failures map to one stable public request
field. The feature suite keeps invalid token, audience, expiry, issuer, and
nonce as distinct preconditions even when the response message is identical:

```php
try {
    $claims = JWT::decode(
        (string) $request->string('id_token'),
        $this->retrieveKeySet(),
    );
} catch (Throwable) {
    throw ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]);
}
```

Claim checks after successful signature verification throw the same validation
message on `id_token`. Do not invent public exception types or report expected
credential failures unless the live controller does so.

## Related References

- [`../external-identity-flow.md`](../external-identity-flow.md)
