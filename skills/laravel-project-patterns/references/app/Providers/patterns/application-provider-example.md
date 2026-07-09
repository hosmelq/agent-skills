# Application Provider Example

## When To Use

Read this focused reference when the task involves application provider example.

## Pattern

### Application Provider Example

```php
<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Actor;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\QueueCheck;
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\Health\Facades\Health;

class ExampleApplicationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureFormRequests();
        $this->configureHealthChecks();
        $this->configureModels();
        $this->configurePasswordValidation();
        $this->configureRateLimiters();
        $this->configureResources();
        $this->configureRouteMacros();
        $this->configureUrls();
        $this->configureVite();
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function configureFormRequests(): void
    {
        FormRequest::failOnUnknownFields();
    }

    private function configureHealthChecks(): void
    {
        Health::checks([
            CacheCheck::new(),
            DatabaseCheck::new(),
            DebugModeCheck::new(),
            OptimizedAppCheck::new(),
            QueueCheck::new()->failWhenHealthJobTakesLongerThanMinutes(2),
            ScheduleCheck::new()->heartbeatMaxAgeInMinutes(2),
        ]);
    }

    private function configureModels(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Model::shouldBeStrict();
        Model::unguard();

        Relation::enforceMorphMap([
            'actor' => Actor::class,
        ]);

        if ($this->app->isProduction()) {
            Model::handleDiscardedAttributeViolationUsing(
                ExampleReporter::discardedAttributeViolationReporter()
            );
            Model::handleLazyLoadingViolationUsing(
                ExampleReporter::lazyLoadingViolationReporter()
            );
            Model::handleMissingAttributeViolationUsing(
                ExampleReporter::missingAttributeViolationReporter()
            );
        }
    }

    private function configurePasswordValidation(): void
    {
        Password::defaults(
            fn (): Password => $this->app->isProduction()
                ? Password::min(8)->uncompromised()
                : Password::min(8),
        );
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('api', function (Request $request): Limit {
            return Limit::perMinute(1000)->by(auth()->id() ?? $request->ip());
        });

        RateLimiter::for('access-code.request', function (Request $request): Limit {
            return Limit::perMinute(5)->by(
                Str::transliterate(sprintf('%s|%s', $request->string('identifier'), $request->ip()))
            );
        });
    }

    private function configureResources(): void
    {
        JsonResource::withoutWrapping();
    }

    private function configureRouteMacros(): void
    {
        RedirectResponse::macro('toast', function (
            string $title,
            null|string $description = null,
            ExampleNoticeVariant $variant = ExampleNoticeVariant::Success,
            int $timeout = 5
        ): RedirectResponse {
            Inertia::flash(ExampleNoticeKey::Toast(), array_filter([
                'description' => $description,
                'timeout' => $timeout * 1000,
                'title' => $title,
                'variant' => $variant->value,
            ], fn (null|int|string $value): bool => $value !== null));

            return $this;
        });
    }

    private function configureUrls(): void
    {
        URL::forceHttps(! $this->app->environment('local', 'testing'));
    }

    private function configureVite(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
```

## Related References

- [`../README.md`](../README.md)
