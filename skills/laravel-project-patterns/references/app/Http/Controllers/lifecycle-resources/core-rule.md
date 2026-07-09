# Core Rule

## When To Use

Read this focused reference when the task involves core rule.

## Pattern

### Core Rule

If the operation sounds like a verb, first ask: "What resource appears, disappears, changes, or is being viewed?"

Then create or reuse a focused controller for that resource and use standard methods:

| Operation wording               | Resource/controller                    | Method    |
| ------------------------------- | -------------------------------------- | --------- |
| log in                          | `AuthenticatedSessionController`       | `store`   |
| log out                         | `AuthenticatedSessionController`       | `destroy` |
| enable a capability             | `ParentRecordCapabilityController`     | `store`   |
| disable a capability            | `ParentRecordCapabilityController`     | `destroy` |
| confirm a pending state         | `ConfirmedParentRecordStateController` | `store`   |
| regenerate codes                | `RecoveryCodeController`               | `store`   |
| list generated codes            | `RecoveryCodeController`               | `index`   |
| create a membership             | `ParentRecordMembershipController`     | `store`   |
| delete a membership             | `ParentRecordMembershipController`     | `destroy` |
| show a printable representation | `PrintableParentRecordController`      | `show`    |
| search records                  | `SearchableParentRecordController`     | `index`   |
| create a generated identifier   | `UniqueParentRecordCodeController`     | `store`   |

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
