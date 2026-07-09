# Executable Context Router

## Required Preflight

Always run this router before reading pattern references or editing. It converts
all touched project paths plus optional operations and concerns into a small,
ordered context pack. Do not replace or skip it with manual routing. Markdown
remains the source of truth; `catalog.json` contains routing metadata only.

This command is the only reference-discovery entrypoint. Before it succeeds, do
not search, list, or open the reference tree with `rg`, `find`, globs, directory
listings, guessed paths, or broad `sed` reads. Afterward, read exact paths from
the result. Search tools remain appropriate for live project code evidence.
Run with plain `php`; do not add an environment wrapper or runtime-version gate.

## Pattern

Pass every touched path in one invocation so the resolver can deduplicate
shared gates and references:

```shell
php /path/to/laravel-project-patterns/scripts/context.php \
  --path=app/Actions/UpdateRecord.php \
  --path=tests/Integration/Actions/UpdateRecordTest.php
```

The resolver infers an operation only when every recognizable filename agrees.
Pass it explicitly when the operation is known, and add only concerns that are
owned by the matched surfaces:

```shell
php /path/to/laravel-project-patterns/scripts/context.php \
  --path=tests/Feature/Http/Controllers/UpdateRecordControllerTest.php \
  --operation=update \
  --concern=delegated-action
```

Use `--list` to discover supported operations, concerns, aliases, owners, and
path rules. The default output contains selected reference paths, mandatory
gates, word counts, hashes, and one compact frontier summary per loaded parent.
One branch can never consume or hide another branch's frontier.

Expand only the parent you need to reveal its immediate children. Use
`--max-options` and `--offset` to page a large branch without loading unrelated
branches:

```shell
php /path/to/laravel-project-patterns/scripts/context.php \
  --path=tests/Feature/Http/Controllers/RecordControllerTest.php \
  --expand=references/tests/Feature/Http/Controllers/README.md \
  --max-options=10
```

Follow the tree one step at a time. Rerun with `--select` for an immediate child
printed by an expanded frontier; repeat earlier selections when walking deeper.
A jump to a non-child fails closed. Multiple `--expand` and interleaved
`--select` values keep every active task surface independently navigable.

```shell
php /path/to/laravel-project-patterns/scripts/context.php \
  --path=tests/Feature/Http/Controllers/RecordControllerTest.php \
  --select=references/tests/Feature/Http/Controllers/actions/README.md \
  --select=references/tests/Feature/Http/Controllers/actions/update.md
```

After the selection is stable, `--include-content` emits the exact selected
Markdown. It never truncates a reference. If the pack exceeds
`--max-references` or `--max-words`, narrow the paths, concerns, or selections,
or raise the limit explicitly. Use `--format=json` for structured consumers.

Unknown paths, unsupported owner/operation combinations, equal-priority path
matches, traversal segments, missing targets, invalid anchors, and malformed
catalog data return a non-zero exit code with a specific error.

Run `php scripts/validate.php` after changing routing metadata, links, or
references, and `php scripts/test.php` after changing resolver behavior. They
use no Composer dependency. `references/MAP.md` remains the human-readable map,
but it is not a reference-discovery alternative. The resolver does not replace
live-file and equivalent-sibling inspection.

If another touched path appears at any point, stop reference discovery and
rerun the command with the complete updated path set before reading another
reference or editing. Previous output is stale for the expanded scope.

## Related References

- [`SKILL.md`](../SKILL.md)
- [`references/MAP.md`](MAP.md)
- [`references/README.md`](README.md)
