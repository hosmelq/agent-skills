# Index Pages

## Purpose

Route index-page work to the collection/mutation contract or paginator
contract without loading both implementations.

## When To Use

Read this router when the task involves a JavaScript or TypeScript index page.

## Required Pattern

- [`index-pages/collection-and-mutation-states.md`](index-pages/collection-and-mutation-states.md): empty/populated states, row actions, pending mutation, and lifecycle availability.
- [`index-pages/pagination-navigation.md`](index-pages/pagination-navigation.md): previous/next, numbered links, disabled entries, and ellipses.

## Coverage Expectations

Select both leaves only when the touched page owns both contracts.

## Do Not

- Do not load paginator implementation for a non-paginated collection.
- Do not drop any empty, pending, lifecycle, or navigation state.

## Related References

- [`../README.md`](../README.md)
