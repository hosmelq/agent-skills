# One-Time-Code Login

## When To Use

Read this focused reference when the task involves one-time-code login.

## Pattern

### One-Time-Code Login

Query an unused, unexpired code for the submitted email, mark it used, then
create or verify the actor before issuing a token. Expired and already-used
rows are distinct feature-test preconditions but share the same public invalid
code response.

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Sessions;

use App\Http\Requests\Api\Sessions\UseOneTimeCodeRequest;
use App\Http\Resources\ActorResource;
use App\Models\Actor;
use App\Models\OneTimeCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OneTimeCodeController
{
    public function __invoke(UseOneTimeCodeRequest $request): JsonResponse
    {
        $oneTimeCode = OneTimeCode::query()
            ->where('code', $request->string('code'))
            ->where('email', $email = $request->string('email'))
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->first();

        throw_if($oneTimeCode === null, ValidationException::withMessages([
            'code' => __('validation.exists', ['attribute' => 'code']),
        ]));

        $oneTimeCode->update(['used_at' => now()]);

        $actor = Actor::query()->firstWhere('email', $email);

        if ($actor === null) {
            $actor = Actor::query()->create([
                'email' => (string) $email,
                'email_verified_at' => now(),
            ]);
        } elseif ($actor->email_verified_at === null) {
            $actor->update(['email_verified_at' => now()]);
        }

        $token = $actor->createToken('Mobile')->plainTextToken;

        return new JsonResponse([
            'access_token' => Str::of($token)->explode('|')->last(),
            'actor' => ActorResource::make($actor),
        ]);
    }
}
```

This pattern does not introduce an action, transaction, or lock. Add a
single-consumption concurrency protocol only when the live application
implements and tests that complete protocol; a one-time-code name alone is not
evidence for `lockForUpdate()`.

## Related References

- [`../api-authentication.md`](../api-authentication.md)
