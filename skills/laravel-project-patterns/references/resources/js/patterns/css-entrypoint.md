# CSS Entrypoint

## When To Use

Read this focused reference when the task involves css entrypoint.

## Pattern

### CSS Entrypoint

The authored CSS entrypoint declares its framework/design-system imports and
plugins. Add an explicit source path for Blade because it sits outside the
frontend source tree scanned by default.

```css
@import 'tailwindcss';
@import '@heroui/styles';

@plugin 'tailwindcss-react-aria-components';

@source '../views';
```

## Related References

- [`../README.md`](../README.md)
