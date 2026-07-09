# React Email Package And Assets

## When To Use

Use this leaf for package scripts and preview/export asset paths.

## Pattern

- The source area is a Nub package at `resources/react-email`.
- Components belong in `resources/react-email/mail/` and match their generated
  Blade view path under `resources/views/mail`.
- Static source assets belong in `resources/react-email/mail/static/`.
- Resolve assets through
  `resources/react-email/mail/_utils/resolve-email-asset-path.ts`: `/static/...`
  in preview and a Laravel `url("/assets/mail/...")` expression after export.
- Preview with `nub run dev`; export with `nub run export`.
- When only scaffolding exists, do not invent template layout, copy, styling,
  or component rules before real source and generated output exist.
- Verify both preview and exported paths for templates using static assets.
- Do not edit `.react-email/**`, create source assets in `public/assets/mail`,
  or use `npx`; use Nub or `nubx`.

### Package Scripts Example

```json
{
  "name": "example-emails",
  "private": true,
  "scripts": {
    "build": "email build --dir mail",
    "dev": "email dev --dir mail",
    "export": "EXPORT=true email export --dir mail --outDir ../views/mail --pretty",
    "postexport": "nub scripts/finalize-email-export.mjs",
    "preexport": "nub scripts/prepare-email-export.mjs"
  }
}
```


### Asset Path Example

```ts
export function resolveEmailAssetPath(path: string): string {
  if (process.env.EXPORT !== 'true') {
    return `/static/${path}`
  }

  return `{{ url("/assets/mail/${path}") }}`
}
```

## Related References

- [Parent router](../README.md)
