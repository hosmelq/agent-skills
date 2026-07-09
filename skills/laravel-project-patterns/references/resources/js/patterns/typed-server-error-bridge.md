# Typed Server Error Bridge

## When To Use

Read this focused reference when the task involves typed server error bridge.

## Pattern

### Typed Server Error Bridge

Keep Inertia form errors at the form boundary and pass field errors to
accessible controls. A shared generic bridge may normalize error keys, but
pages retain their typed payload.

```tsx
import type {FormComponentProps, FormComponentRef, FormComponentSlotProps} from '@inertiajs/core'
import {Form as InertiaForm} from '@inertiajs/react'
import type {
  AllHTMLAttributes,
  FormHTMLAttributes,
  ReactElement,
  ReactNode,
  RefAttributes,
} from 'react'
import {FormContext, FormValidationContext} from 'react-aria-components'

type ServerFormProps<TForm extends object = Record<string, unknown>> = FormComponentProps<TForm> &
  Omit<FormHTMLAttributes<HTMLFormElement>, keyof FormComponentProps<TForm> | 'children'> &
  Omit<AllHTMLAttributes<HTMLFormElement>, keyof FormComponentProps<TForm> | 'children'> & {
    children: ReactNode | ((props: FormComponentSlotProps<TForm>) => ReactNode)
  }

export function ServerForm<TForm extends object = Record<string, unknown>>({
  action,
  children,
  ref,
  ...otherProps
}: ServerFormProps<TForm> & RefAttributes<FormComponentRef<TForm>>): ReactElement {
  return (
    <InertiaForm<TForm> action={action} ref={ref} {...otherProps}>
      {(slotProps) => (
        <FormContext.Provider value={{validationBehavior: 'aria'}}>
          <FormValidationContext.Provider value={slotProps.errors}>
            {typeof children === 'function' ? children(slotProps) : children}
          </FormValidationContext.Provider>
        </FormContext.Provider>
      )}
    </InertiaForm>
  )
}
```

Inputs use stable `name` values that match Form Request keys and render their
server validation messages through the design-system field contract.

## Related References

- [`../README.md`](../README.md)
