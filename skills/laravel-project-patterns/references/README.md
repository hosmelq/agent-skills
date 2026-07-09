# Reference Skeleton

## Purpose

Define the canonical markdown structure for every reference document under `references/**`.

## When To Use

Use this contract when creating or updating any skill reference markdown file.

## Required Pattern

Use two document roles.

Router references select the applicable branch and own cross-cutting rules. Use
these H2 sections in this exact order:

1. `## Purpose`
2. `## When To Use`
3. `## Required Pattern`
4. `## Coverage Expectations`
5. `## Do Not`
6. `## Related References`

Focused leaves, including navigation maps under `references/maps/**`, inherit
the router's cross-cutting rules and use only:

1. `## When To Use`
2. `## Pattern`
3. `## Related References`

Every router link must say what decision or scenario makes the leaf applicable.
Keep the route from `SKILL.md` to a domain router and then to its leaf short.
Do not repeat generic coverage or prohibition text in every leaf.

Preserve all technical pattern coverage, datasets, and snippets. When an example uses a real module/entity name, convert it to a complete synthetic example instead of deleting it.

References must stay grounded in live repository evidence. When updating a reference, read the exact files in the matching path plus equivalent live siblings with the same precondition, operation, ownership boundary, and outcome before editing the prose. Directory proximity alone does not make a sibling authoritative. If the pattern only appears in one area, say that it is a current local pattern instead of turning it into a broad rule.

When a reference touches model integration coverage, link to `references/tests/Integration/Models/README.md` instead of duplicating its full policy text.

## Coverage Expectations

This file defines documentation structure expectations only. Router references
define coverage expectations for the project code they map to; focused leaves
preserve the selected pattern or example.

For controller references, coverage expectations must include both action order and nested binding boundaries because those are part of the project contract, not optional examples.

## Do Not

- Do not drop technical coverage during normalization; convert real module/entity examples to synthetic placeholders.
- Do not weaken the model integration policy above.
- Do not make agents read sibling leaves before they can determine which branch applies.

## Related References

- [`SKILL.md`](../SKILL.md)
- [`references/MAP.md`](MAP.md)
- [`references/tests/Integration/Models/README.md`](tests/Integration/Models/README.md)
- [`references/tests/Unit/Models/README.md`](tests/Unit/Models/README.md)
