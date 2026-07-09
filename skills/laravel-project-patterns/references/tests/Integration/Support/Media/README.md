# tests/Integration/Support/Media

## Purpose

This reference defines conventions for media support integration tests under `tests/Integration/Support/Media`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Integration/Support/Media/<Class>Test.php` for media-library support classes such as path generators, file namers, and media metadata helpers.

### Common Setup

- Fake the configured disk with `Storage::fake('public')`.
- Create the generic test support model.
- Attach media with `UploadedFile::fake()->image(...)`.
- Assert media paths, file names, UUIDs, custom properties, or generated metadata.

### Path Generator Pattern

Cover:

- the media UUID is used as the base path;
- configured prefixes are included before the UUID path.

Use `Str::startsWith(...)` for path prefix contracts.

```php
it('uses the media uuid as the base path', function (): void {
    Storage::fake('public');

    $model = ExampleModel::query()->create();

    $media = $model->addMedia(UploadedFile::fake()->image('image.jpg'))
        ->toMediaCollection();

    $path = $media->getPathRelativeToRoot();

    expect(Str::startsWith($path, $media->uuid.'/'))->toBeTrue();
});

it('includes the configured prefix before the media uuid path', function (): void {
    Storage::fake('public');

    config(['media-library.prefix' => 'uploads']);

    $model = ExampleModel::query()->create();

    $media = $model->addMedia(UploadedFile::fake()->image('image.jpg'))
        ->toMediaCollection();

    $path = $media->getPathRelativeToRoot();

    expect(Str::startsWith($path, 'uploads/'.$media->uuid.'/'))->toBeTrue();
});
```

### File Namer Pattern

Cover:

- the filename base is a UUID;
- the original extension is preserved by the Media Library integration around the generated basename.

`FileNamer` itself returns the UUID basename; the package appends/preserves the extension when storing media. Use `pathinfo(...)` and `Str::isUuid(...)` for assertions.

```php
it('uses a uuid file name when media is added', function (): void {
    Storage::fake('public');

    $model = ExampleModel::query()->create();

    $media = $model->addMedia(UploadedFile::fake()->image('photo.png'))
        ->toMediaCollection();

    $fileName = pathinfo($media->file_name, PATHINFO_FILENAME);
    $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);

    expect(Str::isUuid($fileName))->toBeTrue()
        ->and($extension)->toBe('png');
});
```

### Do Not

- Do not hit real disks or cloud storage.
- Do not use application models when the behavior is generic to media support.

## Coverage Expectations

Read the live support hook and tests for the same package integration and
observable output. Do not copy unrelated media cases for symmetry.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/Support/README.md`](../../../../app/Support/README.md)
- [`references/app/Listeners/README.md`](../../../../app/Listeners/README.md)
