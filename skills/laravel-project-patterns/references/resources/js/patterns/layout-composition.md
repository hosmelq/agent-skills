# Layout Composition

## When To Use

Read this focused reference when the task involves layout composition.

## Pattern

### Layout Composition

Use small layout primitives for navigation, page width, breadcrumbs, headings,
and actions. Page components provide domain content, not repeated application
chrome.

```tsx
import type {PropsWithChildren, ReactNode} from 'react'

interface PageLayoutProps extends PropsWithChildren {
  actions?: ReactNode
  description?: ReactNode
  title: ReactNode
}

export function PageLayout({actions, children, description, title}: PageLayoutProps) {
  return (
    <main className="mx-auto w-full max-w-7xl px-4 py-8">
      <header className="mb-6 flex items-start justify-between gap-4">
        <div>
          <h1 className="text-2xl font-semibold">{title}</h1>
          {description && <p className="text-foreground-500 mt-1 text-sm">{description}</p>}
        </div>
        {actions}
      </header>
      {children}
    </main>
  )
}
```

Keep the modal root in the layout layer so every page can host stacked modal
responses without duplicating it:

```tsx
import {ModalRoot} from '@inertiaui/modal-react'
import type {PropsWithChildren} from 'react'

export function ModalLayout({children}: PropsWithChildren) {
  return (
    <>
      {children}
      <ModalRoot />
    </>
  )
}
```

Responsive content, side navigation, contextual `Workspace` chrome, and modal
root are separate layout variants. Compose the variants selected by the
entrypoint; page files should not reproduce them.

## Related References

- [`../README.md`](../README.md)
