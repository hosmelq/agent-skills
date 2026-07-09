# Index Pages: Pagination Navigation

## When To Use

Read this leaf when pagination navigation is in scope.

## Pattern

The paginator must support previous/next links, numbered links, disabled
entries, and non-clickable ellipses.

```tsx
export function Paginator({pagination}: {pagination: LaravelPaginator<unknown>}) {
  return (
    <Pagination>
      <Pagination.Summary>
        Showing page {pagination.meta.current_page} of {pagination.meta.last_page}
      </Pagination.Summary>
      <Pagination.Content>
        <Pagination.Item>
          <Pagination.Previous
            isDisabled={!pagination.links.prev}
            onPress={() => {
              if (pagination.links.prev) {
                router.visit(pagination.links.prev)
              }
            }}
          >
            <Pagination.PreviousIcon />
            Previous
          </Pagination.Previous>
        </Pagination.Item>
        {pagination.meta.links.slice(1, -1).map((link, index) =>
          link.label === '...' || link.label === '&hellip;' ? (
            <Pagination.Item key={`ellipsis-${index}`}>
              <Pagination.Ellipsis />
            </Pagination.Item>
          ) : (
            <Pagination.Item key={`${link.label}-${index}`}>
              <Pagination.Link
                isActive={link.active}
                isDisabled={!link.url}
                onPress={() => {
                  if (link.url) {
                    router.visit(link.url)
                  }
                }}
              >
                {link.label}
              </Pagination.Link>
            </Pagination.Item>
          ),
        )}
        <Pagination.Item>
          <Pagination.Next
            isDisabled={!pagination.links.next}
            onPress={() => {
              if (pagination.links.next) {
                router.visit(pagination.links.next)
              }
            }}
          >
            Next
            <Pagination.NextIcon />
          </Pagination.Next>
        </Pagination.Item>
      </Pagination.Content>
    </Pagination>
  )
}
```

## Related References

- [`../index-pages.md`](../index-pages.md)
