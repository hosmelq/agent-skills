# Local Process Orchestration

## When To Use

Read this focused reference when the task involves local process orchestration.

## Pattern

### Local Process Orchestration

When the repository uses Solo, keep its processes in `solo.yml`. Editing this
file does not authorize starting Solo; invoke Solo only when the user explicitly
asks. Keep processes independently restartable and distinguish long-running
workers from one-shot build/export commands.

```yaml
icon: null

name: example-app

processes:
  'artisan: octane:start':
    auto_restart: true
    auto_start: false
    command: php artisan octane:start --server=frankenphp --watch
  'artisan: pail':
    auto_restart: true
    auto_start: true
    command: php artisan pail
  'artisan: queue:work':
    auto_restart: true
    auto_start: true
    command: php artisan queue:work database --timeout=180 --tries=3
  'artisan: schedule:work':
    auto_restart: true
    auto_start: true
    command: php artisan schedule:work --whisper
  'nub: build':
    auto_restart: false
    auto_start: false
    command: nub run build
  'nub: dev':
    auto_restart: true
    auto_start: true
    command: nub run dev
  'react-email: nub build':
    auto_start: false
    command: nub --cwd resources/react-email run build
  'react-email: nub dev':
    auto_start: false
    command: nub --cwd resources/react-email run dev
  'react-email: nub export':
    auto_start: false
    command: nub --cwd resources/react-email run export
    restart_when_changed:
      - resources/react-email/mail/**/*.tsx
```

The relevant process union is:

- application HTTP server;
- application log viewer;
- queue worker;
- scheduler;
- frontend build and dev server;
- mail-template build, preview, and watched export when present.

Do not add a process merely for symmetry when the application does not use the
corresponding subsystem.

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
