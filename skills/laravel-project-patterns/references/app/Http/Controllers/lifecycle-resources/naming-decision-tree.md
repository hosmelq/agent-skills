# Naming Decision Tree

## When To Use

Read this focused reference when the task involves naming decision tree.

## Pattern

### Naming Decision Tree

1. **Existing canonical resource:** If the action is ordinary create/read/update/delete of the model, keep it in the model's controller.
   - `ParentRecordController@store`
   - `ParentRecordController@update`
   - `ParentRecordController@destroy`

2. **Nested child resource:** If the action creates or removes a relationship-like thing, name that thing.
   - `ParentRecordMembershipController@store` / `destroy`
   - `ActorPasskeyController@store` / `destroy`
   - `WorkspaceInvitationController@store` / `destroy`

3. **Singleton capability or lifecycle state:** If the parent can have only one such capability/state, use a singular nested resource and pair `store` with `destroy`.
   - `ParentRecordActivationController@store` activates a record.
   - `ParentRecordActivationController@destroy` deactivates a record.
   - `ParentRecordCapabilityController@store` enables a capability.
   - `ParentRecordCapabilityController@destroy` disables a capability.

4. **Negative-state timestamp:** If the persisted domain concept is explicitly the negative state, such as `deactivated_at`, prefer naming the resource after that state.
   - `ParentRecordDeactivationController@store` sets `deactivated_at`.
   - `ParentRecordDeactivationController@destroy` clears `deactivated_at`.
   - This reads better than `ParentRecordActivationController@destroy` when the column, policy, and UI are all framed as deactivation.

5. **Positive capability wording:** If the UI/business language is "activation exists" rather than "deactivation exists", use the positive capability.
   - `ParentRecordActivationController@store` creates activation.
   - `ParentRecordActivationController@destroy` removes activation.

6. **Adjective/result-state resource:** Use adjective names when the resource is naturally a result view or authenticated/confirmed state.
   - `AuthenticatedSessionController`, not `SessionAuthenticationController`, for login/logout.
   - `ConfirmedParentRecordStateController` for a confirmation event.
   - `ConfirmedStatusController` for a status read.
   - `PrintableParentRecordController` for a printable representation.

7. **Status enum or multi-state workflow:** If the operation changes among multiple states, do not invent one controller per transition unless each transition has its own permissions, validation, side effects, or UI. Use a status resource and `update`.
   - `ParentRecordStatusController@update` for multi-state updates.
   - Use separate lifecycle controllers for high-risk transitions with different authorization or side effects.

8. **Command with complex domain logic:** Keep the controller resourceful, but push the logic into an action.
   - `ParentRecordDeactivationController@store` calls `DeactivateParentRecord`.
   - `UniqueParentRecordCodeController@store` calls `GenerateParentRecordCode`.
   - A service/action name can be verb-first; a controller name should remain resource-first.
   - For lifecycle actions, pass only the target model and independent business inputs. Scoped bindings and policies already own route hierarchy and ownership.

9. **Pure read representation:** If no mutation happens, pick `index` or `show`.
   - Search collection: `SearchableParentRecordController@index`.
   - Printable single resource: `PrintableParentRecordController@show`.
   - Export collection: `ParentRecordExportController@store` when it creates an export job/file; `ParentRecordExportController@show` when it only streams an existing export.

10. **One-off side effects:** Avoid `__invoke` as a shortcut for vague verbs. Use invokable controllers for route endpoints that are already first-class single-action resources in nearby code, or when framework precedent uses invokable view/verification controllers.

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
