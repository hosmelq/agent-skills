# One-Time-Code Request

## When To Use

Read this focused reference when the task involves one-time-code request.

## Pattern

### One-Time-Code Request

The request endpoint validates a delivery address, delegates code generation,
and sends the notification to a routable recipient. It does not issue a token.

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Sessions;

use App\Actions\Sessions\GenerateOneTimeCode;
use App\Http\Requests\Api\Sessions\RequestOneTimeCodeRequest;
use App\Notifications\OneTimeCodeNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class RequestOneTimeCodeController
{
    public function __invoke(
        RequestOneTimeCodeRequest $request,
        GenerateOneTimeCode $generate,
    ): JsonResponse {
        $oneTimeCode = $generate->handle(
            $email = (string) $request->string('email'),
        );

        Notification::route('mail', $email)
            ->notify(new OneTimeCodeNotification($oneTimeCode));

        return new JsonResponse();
    }
}
```

Code generation owns global uniqueness, cleanup, expiry, and collision retries.
The controller owns only validated delivery, action invocation, notification,
and the empty `200` JSON response.

## Related References

- [`../api-authentication.md`](../api-authentication.md)
