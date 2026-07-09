# Validation Dataset Catalog

## Purpose

Route controller validation work to the smallest dataset catalog that matches
the request rules.

## When To Use

Load this map after the action reference and before adding cases to an existing
`validates fields` dataset. Read only the leaves whose rules occur in the Form
Request.

## Required Pattern

Merge every applicable, semantically unique case into the action's single
ordered dataset. Keep one minimal invalid payload per rule or coupled rule
family and assert every expected message.

- Name each dataset case after the validation rule being proved, including
  parameters such as `different:record_id`, `required_with:latitude`, or
  `gte:minimum_value`. Do not prefix the rule with the field name. Group fields
  that share the same rule into one case; use a parenthetical qualifier only
  when the same rule needs distinct value-type or boundary cases.
- [`dataset-catalog/text-and-format.md`](dataset-catalog/text-and-format.md):
  string length, string type, email, phone, timezone, and hexadecimal color
  rules.
- [`dataset-catalog/numeric-bounds.md`](dataset-catalog/numeric-bounds.md):
  precision, integer/numeric type, absolute bounds, and bounds relative to
  another submitted field.
- [`dataset-catalog/coordinate-pairs.md`](dataset-catalog/coordinate-pairs.md):
  both edges of coordinate ranges plus `required_with` and `present_with` in
  both directions.
- [`dataset-catalog/public-id-and-managed-inputs.md`](dataset-catalog/public-id-and-managed-inputs.md):
  public-ID type/difference rules and server-managed input rejection.
- [`store-validates-fields.md`](store-validates-fields.md): the baseline store
  dataset and store-only catalog rules.
- [`update-validates-fields.md`](update-validates-fields.md): the baseline
  update dataset, stored-value comparisons, and update-only catalog rules.

## Coverage Expectations

The union of the baseline store/update datasets and the focused leaves covers
every locally observed dataset family. Similar rules with distinct parameters
remain separate examples, such as `decimal:0,2` versus `decimal:0,4`, or
`max:3` versus `max:2000`.

## Do Not

- Do not load every leaf when the request uses only one rule family.
- Do not copy a case already present in the action's dataset.
- Do not rename a rule into a broader category if its parameter changes the
  boundary being proved.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog/store-update-validation-patterns.md`](../pattern-catalog/store-update-validation-patterns.md)
