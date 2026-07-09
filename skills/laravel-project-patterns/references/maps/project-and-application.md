# Project And Application Map

## When To Use

Use this map to select one project or application-layer router.

## Pattern

- [`project/README.md`](../project/README.md): routes, configuration, localization,
  bootstrap/public entrypoints, seeders, and tooling PHP.
  - `project/patterns/*.md`: focused supporting-PHP patterns selected by the
    project router.
  - [`project/configuration-and-tooling.md`](../project/configuration-and-tooling.md):
    Composer, frontend tooling, static analysis, hooks, and local orchestration.
  - `project/configuration-and-tooling/*.md`: focused tooling leaves selected by
    the tooling router.
- [`app/README.md`](../app/README.md): shared Laravel application-layer contract.
  - [`app/php-shape.md`](../app/php-shape.md): global PHP shape and synthetic example.
  - [`app/http-layer.md`](../app/http-layer.md): cross-cutting controller, Form Request, and resource rules.
  - [`app/test-support.md`](../app/test-support.md): global test setup, helpers, HTTP fakes, and support infrastructure.
  - [`app/Actions/README.md`](../app/Actions/README.md): action routing.
    - `app/Actions/patterns/*.md`: focused persistence, state, ordering,
      relationship, and concurrency patterns.
  - [`app/Console/Commands/README.md`](../app/Console/Commands/README.md): Artisan
    command patterns.
  - [`app/Enums/README.md`](../app/Enums/README.md): enum routing.
    - `app/Enums/patterns/*.md`: focused enum families.
  - [`app/Exceptions/README.md`](../app/Exceptions/README.md): domain exception
    patterns.
  - [`app/Http/Controllers/README.md`](../app/Http/Controllers/README.md):
    controller routing.
    - `app/Http/Controllers/patterns/*.md`: shared controller leaves.
    - [`app/Http/Controllers/api-authentication.md`](../app/Http/Controllers/api-authentication.md):
      API authentication map.
    - `app/Http/Controllers/api-authentication/**/*.md`: focused external
      identity, session, token, and one-time-code leaves.
    - [`app/Http/Controllers/lifecycle-resources.md`](../app/Http/Controllers/lifecycle-resources.md):
      lifecycle-resource map.
    - `app/Http/Controllers/lifecycle-resources/*.md`: focused lifecycle leaves.
  - [`app/Http/Middleware/README.md`](../app/Http/Middleware/README.md): middleware
    patterns.
  - [`app/Http/Requests/README.md`](../app/Http/Requests/README.md): Form Request
    routing.
    - `app/Http/Requests/patterns/*.md`: focused request-rule and normalization
      leaves.
  - [`app/Http/Resources/README.md`](../app/Http/Resources/README.md): JSON resource
    patterns.
  - [`app/Listeners/README.md`](../app/Listeners/README.md): listener patterns.
  - [`app/Models/README.md`](../app/Models/README.md): Eloquent model routing.
    - [`app/Models/Concerns/README.md`](../app/Models/Concerns/README.md): reusable
      model concerns.
    - [`app/Models/World/README.md`](../app/Models/World/README.md): non-default
      reference-data connection.
    - `app/Models/patterns/*.md`: focused model leaves.
  - [`app/Notifications/README.md`](../app/Notifications/README.md): notification
    patterns.
  - [`app/Policies/README.md`](../app/Policies/README.md): policy patterns.
  - [`app/Providers/README.md`](../app/Providers/README.md): provider routing.
    - `app/Providers/patterns/*.md`: focused application-wide configuration
      leaves.
  - [`app/Support/README.md`](../app/Support/README.md): support-class patterns.
  - [`app/functions.php.md`](../app/functions.php.md): global helper patterns.

## Related References

- [Parent router](../MAP.md)
