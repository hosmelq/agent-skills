# React Email Export Script

## When To Use

Use this leaf for the complete generated-view and static-asset export script.

## Pattern

- The lifecycle runs `preexport`, `export`, then `postexport`.
- `preexport` recreates `resources/views/mail` and clears `public/assets/mail`.
- `export` runs
  `EXPORT=true email export --dir mail --outDir ../views/mail --pretty`.
- `postexport` renames `.html` files to `.blade.php` and moves `static/` assets
  to `public/assets/mail`.
- Laravel mailables and notifications reference exported views. Do not
  hand-author generated Blade output or bypass cleanup.
- Inspect generated Blade output before documenting the first broad template
  pattern. Cover consumers with mail/notification tests that own the same
  rendered contract.
- Keep export changes within the source, generated views, and generated asset
  paths unless Laravel integration is explicitly in scope.

### Export Script Example

```js
import {mkdir, readdir, rename, rm, stat} from 'node:fs/promises'
import {basename, join} from 'node:path'

import {mailViewsDir, publicAssetsDir, publicAssetsMailDir} from './config.mjs'

await rm(mailViewsDir, {force: true, recursive: true})
await rm(publicAssetsMailDir, {force: true, recursive: true})

await mkdir(mailViewsDir, {recursive: true})
await mkdir(publicAssetsDir, {recursive: true})

async function renameHtmlFiles(directory) {
  const entries = await readdir(directory, {withFileTypes: true})

  await Promise.all(
    entries.map(async (entry) => {
      const entryPath = join(directory, entry.name)

      if (entry.isDirectory()) {
        await renameHtmlFiles(entryPath)
        return
      }

      if (!entry.isFile() || !entry.name.endsWith('.html')) {
        return
      }

      const bladePath = join(directory, `${basename(entry.name, '.html')}.blade.php`)

      await rm(bladePath, {force: true})
      await rename(entryPath, bladePath)
    }),
  )
}

await renameHtmlFiles(mailViewsDir)

const staticDir = join(mailViewsDir, 'static')

try {
  const staticStats = await stat(staticDir)

  if (staticStats.isDirectory()) {
    await mkdir(publicAssetsDir, {recursive: true})
    await rm(publicAssetsMailDir, {recursive: true, force: true})
    await rename(staticDir, publicAssetsMailDir)
  }
} catch (error) {
  if (error?.code !== 'ENOENT') {
    throw error
  }
}
```

## Related References

- [Parent router](../README.md)
