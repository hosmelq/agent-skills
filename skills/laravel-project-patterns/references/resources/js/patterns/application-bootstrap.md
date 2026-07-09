# Application Bootstrap

## When To Use

Read this focused reference when the task involves application bootstrap.

## Pattern

### Application Bootstrap

Compose the application once: resolve pages, assign the default layout, mount
shared providers, register progress/toast behavior, and keep development-only
diagnostics out of production.

```tsx
import '@fortawesome/fontawesome-svg-core/styles.css'
import './index.css'
import {config} from '@fortawesome/fontawesome-svg-core'
import {toast as heroToast, RouterProvider, Toast} from '@heroui/react'
import {createInertiaApp, router, type ResolvedComponent} from '@inertiajs/react'
import {ModalStackProvider} from '@inertiaui/modal-react'
import * as Sentry from '@sentry/react'
// @ts-expect-error - No types available for the development-only studio.
import {startStudio} from 'cssstudio'
import posthog from 'posthog-js'

import {App as AppLayout} from '#/layouts/App'
import {Modal as ModalLayout} from '#/layouts/Modal'
import {Workspace as WorkspaceLayout} from '#/layouts/Workspace'

if (import.meta.env.DEV) {
  startStudio()
}

if (import.meta.env.PROD) {
  Sentry.init({
    dsn: 'https://public@example.ingest.invalid/1',
    integrations: [Sentry.browserProfilingIntegration(), Sentry.browserTracingIntegration()],
    tracesSampleRate: 0.25,
  })

  posthog.init('example-public-key', {
    api_host: 'https://us.i.posthog.com',
    defaults: '2025-11-30',
  })
}

config.autoAddCss = false

const standalonePagePrefixes = ['auth/']

router.on('flash', (event) => {
  const {toast} = event.detail.flash

  if (!toast) {
    return
  }

  heroToast(toast.title, {
    description: toast.description,
    timeout: toast.timeout,
    variant: toast.variant,
  })
})

void createInertiaApp({
  layout: (name) => {
    if (standalonePagePrefixes.some((prefix) => name.startsWith(prefix))) {
      return ModalLayout
    }

    return [ModalLayout, AppLayout, WorkspaceLayout]
  },
  progress: {
    color: 'var(--accent)',
  },
  resolve: (name) => {
    const pages = import.meta.glob<ResolvedComponent>([
      './pages/**/*.tsx',
      '!./pages/**/_components/**/*.tsx',
    ])
    const page = pages[`./pages/${name}.tsx`]

    if (typeof page === 'undefined') {
      throw new Error(`Page not found: ${name}.`)
    }

    return page()
  },
  strictMode: true,
  title: (title) => (title.length > 0 ? `${title} - Example App` : 'Example App'),
  withApp: (app) => (
    <RouterProvider navigate={(path, routerOptions) => router.visit(path, routerOptions)}>
      <ModalStackProvider>{app}</ModalStackProvider>
      <Toast.Provider />
    </RouterProvider>
  ),
})
```

Development tools are gated with `import.meta.env.DEV`; monitoring and
analytics are gated with `import.meta.env.PROD`. Flash-to-toast translation,
icon-library CSS ownership, layout selection, missing-page failure, progress,
title composition, router bridging, modal stack, and toast root are separate
bootstrap contracts—preserve every one that the live entrypoint owns.

## Related References

- [`../README.md`](../README.md)
