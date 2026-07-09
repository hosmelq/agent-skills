# Authored TypeScript Contracts

## When To Use

Read this focused reference when the task involves authored typescript contracts.

## Pattern

### Authored TypeScript Contracts

Keep server contracts explicit, including decimal strings and nullable
timestamps.

```ts
export interface LaravelPaginator<T> {
  data: T[]
  links: {
    first: string
    last: string
    next: null | string
    prev: null | string
  }
  meta: {
    current_page: number
    from: null | number
    last_page: number
    links: Array<{
      active: boolean
      label: string
      url: null | string
    }>
    path: string
    per_page: number
    to: null | number
    total: number
  }
}

export interface UnprocessableContentResponse {
  errors: Record<string, string[]>
  message: string
}

export interface ChildRecordResource {
  created_at: string
  deactivated_at: null | string
  description: null | string
  id: string
  maximum_value: null | string
  minimum_value: string
  name: string
  updated_at: string
}
```

Augment shared Inertia props and router options at the framework boundary:

```ts
import type {VisitOptions} from '@inertiajs/core'

import type {NoticeVariant} from '#/types/generated/enums'
import type {ActorResource, WorkspaceResource} from '#/types/resources'

declare module '@inertiajs/core' {
  interface InertiaConfig {
    flashDataType: {
      toast?: {
        description?: string
        timeout: number
        title: string
        variant: NoticeVariant
      }
    }
    sharedPageProps: {
      auth: {
        actor: null | ActorResource
        workspace: null | WorkspaceResource
      }
    }
  }
}

declare module '@react-types/shared' {
  interface RouterConfig {
    routerOptions: VisitOptions
  }
}
```

## Related References

- [`../README.md`](../README.md)
