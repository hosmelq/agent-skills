# Missing and Changed Email Rules

## When To Use

Read this focused reference when the task involves missing and changed email rules.

## Pattern

### Missing and Changed Email Rules

Email is conditional provider data, not the identity key. Query the stable
provider subject first. The synthetic fields below represent one provider's
columns on the actor:

```php
$email = $claims['email'] ?? null;
$actor = Actor::query()->firstWhere('provider_id', $claims['subject']);

if ($actor !== null) {
    if ($email !== null && $actor->provider_email !== $email) {
        $actor->update(['provider_email' => $email]);
    }

    return $actor;
}

throw_if(
    $email === null,
    ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.invalid_credentials'),
    ]),
);
```

Do not replace the actor's primary email when an already-linked provider later
reports a different email. For a new subject, an existing primary email is a
conflict; do not silently attach the provider to that account:

```php
throw_unless(
    Actor::query()->firstWhere('email', $email) === null,
    ValidationException::withMessages([
        'id_token' => __('auth.providers.identity.account_conflict'),
    ]),
);

$actor = Actor::query()->create([
    'email' => $email,
    'email_verified_at' => now(),
    'first_name' => (string) $request->string('first_name'),
    'last_name' => (string) $request->string('last_name'),
    'provider_email' => $email,
    'provider_id' => $claims['subject'],
]);
```

An SDK-backed provider may supply given/family names in verified claims
instead; keep request-supplied names and provider-claim names as separate
provider contracts. If the provider owns a different conflict or missing-claim
contract, give it a separate controller and request while retaining the same
response boundary.
Do not invent a generic identity table, reconciliation action, transaction, or
claim shape when the live project stores provider columns directly on actors.

## Related References

- [`../api-authentication.md`](../api-authentication.md)
