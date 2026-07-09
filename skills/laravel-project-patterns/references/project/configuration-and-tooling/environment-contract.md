# Environment Contract

## When To Use

Read this focused reference when the task involves environment contract.

## Pattern

### Environment Contract

Keep committed environment examples paired:

- `.env.example` documents normal local defaults and every required key.
- `.env.testing.example` selects isolated test services and never points at local
  developer or production data.
- Boolean/integer/list values are strings in the environment and are cast or
  parsed once in config.

```dotenv
# .env.example
ADMIN_EMAILS=admin@example.test
APPLE_CLIENT_ID=
APP_DEBUG=true
APP_ENV=local
APP_KEY=
APP_NAME=Example
APP_URL=http://example.test
AWS_ACCESS_KEY_ID=
AWS_BUCKET=example
AWS_DEFAULT_REGION=auto
AWS_ENDPOINT=
AWS_SECRET_ACCESS_KEY=
AWS_USE_PATH_STYLE_ENDPOINT=true
DB_CONNECTION=pgsql
DB_URL=postgres://postgres@127.0.0.1:5432/example
FILESYSTEM_DISK=local
GOOGLE_CLIENT_ID=
HEALTH_SLACK_WEBHOOK_URL=
LOG_STACK=single
LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter
MAIL_FROM_ADDRESS=no-reply@example.test
MAIL_FROM_NAME="${APP_NAME}"
MAIL_MAILER=smtp
RESEND_API_KEY=
SENTRY_LARAVEL_DSN=
SENTRY_RELEASE=
```

```dotenv
# .env.testing.example
APPLE_CLIENT_ID=example-apple-client-id
APP_DEBUG=true
APP_ENV=testing
APP_KEY=
APP_NAME=Example
APP_URL=http://example.test
BCRYPT_ROUNDS=4
CACHE_STORE=array
DB_CONNECTION=pgsql
DB_URL=postgres://postgres@127.0.0.1:5432/example_testing
GOOGLE_CLIENT_ID=example-google-client-id
MAIL_MAILER=array
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
```

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
