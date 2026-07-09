# Identity Reconciliation Controller

## When To Use

Read this reference when implementing or testing external identity reconciliation at the HTTP boundary.

## Pattern

### Identity Reconciliation Controller

The HTTP controller verifies provider credentials, reconciles the provider
subject against provider-specific actor columns, and issues a token. The
SDK-backed variant has this flow:

1. reject an unverifiable credential;
2. log in an actor already linked by provider subject;
3. update only that actor's provider email when the provider reports a change;
4. reject a new subject whose email already belongs to an actor;
5. create and verify a new actor with the provider subject and email;
6. issue one token without Sanctum's storage-ID prefix.

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Sessions;

use App\Http\Requests\Api\Sessions\SdkIdentityRequest;
use App\Http\Resources\ActorResource;
use App\Models\Actor;
use ExternalIdentityClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SdkAuthenticatedSessionController
{
    public function __invoke(
        SdkIdentityRequest $request,
        ExternalIdentityClient $client,
    ): JsonResponse {
        $client->setClientId(Config::string('services.identity.client_id'));

        /** @var array{email: string, family_name: null|string, given_name: null|string, sub: string}|false $claims */
        $claims = $client->verifyIdToken(
            (string) $request->string('id_token'),
        );

        throw_if($claims === false, ValidationException::withMessages([
            'id_token' => __('auth.providers.identity.invalid_credentials'),
        ]));

        $actor = Actor::query()->firstWhere('provider_id', $claims['sub']);

        if ($actor !== null) {
            if ($actor->provider_email !== $claims['email']) {
                $actor->update(['provider_email' => $claims['email']]);
            }

            $token = $actor->createToken('Mobile (Identity)')->plainTextToken;

            return new JsonResponse([
                'access_token' => Str::of($token)->explode('|')->last(),
                'actor' => ActorResource::make($actor),
            ]);
        }

        throw_unless(
            Actor::query()->firstWhere('email', $claims['email']) === null,
            ValidationException::withMessages([
                'id_token' => __('auth.providers.identity.account_conflict'),
            ]),
        );

        $actor = Actor::query()->create([
            'email' => $claims['email'],
            'email_verified_at' => now(),
            'first_name' => $claims['given_name'] ?? null,
            'last_name' => $claims['family_name'] ?? null,
            'provider_email' => $claims['email'],
            'provider_id' => $claims['sub'],
        ]);

        $token = $actor->createToken('Mobile (Identity)')->plainTextToken;

        return new JsonResponse([
            'access_token' => Str::of($token)->explode('|')->last(),
            'actor' => ActorResource::make($actor),
        ]);
    }
}
```

Do not introduce a reconciliation action, generic identity table, or
transaction unless the live application has that boundary. Keep the
missing-email rules of a signed-token provider in
`../missing-and-changed-email-rules.md`.

## Related References

- [`../external-identity-flow.md`](../external-identity-flow.md)
