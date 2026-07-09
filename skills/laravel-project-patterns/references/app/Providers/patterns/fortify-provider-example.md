# Fortify Provider Example

## When To Use

Read this focused reference when the task involves fortify provider example.

## Pattern

### Fortify Provider Example

Authentication providers keep Fortify action registration, views, redirect callbacks, and framework throttle definitions together:

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Auth\CreateNewActor;
use App\Actions\Auth\ResetActorPassword;
use App\Actions\Auth\UpdateActorPassword;
use App\Actions\Auth\UpdateActorProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class ExampleFortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewActor::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
        Fortify::resetUserPasswordsUsing(ResetActorPassword::class);
        Fortify::updateUserPasswordsUsing(UpdateActorPassword::class);
        Fortify::updateUserProfileInformationUsing(UpdateActorProfileInformation::class);

        Fortify::loginView(function () {
            return Inertia::render('auth/Login');
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(sprintf(
                '%s|%s',
                Str::lower((string) $request->string(Fortify::username())),
                $request->ip()
            ));

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
```

## Related References

- [`../README.md`](../README.md)
