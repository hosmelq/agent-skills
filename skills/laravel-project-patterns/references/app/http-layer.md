# Application HTTP Layer

## When To Use

Use this router when an application change crosses controllers, Form Requests, or
JSON resources and the focused domain router has not yet been selected.

## Pattern

### Focused References

- [Application HTTP Controllers](http-layer/controllers.md): Use this leaf when a change crosses controller concerns before selecting the focused controller router.
- [Application Form Requests](http-layer/form-requests.md): Use this leaf when a change crosses Form Request concerns before selecting the focused request router.
- [Application HTTP Resources](http-layer/resources.md): Use this leaf when a change crosses resource concerns before selecting the focused resource router.

## Related References

- [`references/app/README.md`](README.md)
- [`references/app/Http/Controllers/README.md`](Http/Controllers/README.md)
- [`references/app/Http/Requests/README.md`](Http/Requests/README.md)
- [`references/app/Http/Resources/README.md`](Http/Resources/README.md)
