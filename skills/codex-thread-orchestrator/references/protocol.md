# Compact Visible-Task Protocol

## State

For verified parent `<p>` use:

```text
<root>/<p>/parent
<root>/<p>/control.md
<root>/<p>/active
<root>/<p>/children/<child-id>.md
```

`parent` is permanent; `active` holds only the current objective. Extend the
`$durable-workflow-control` cursor in `control.md` with this routing index:

```text
parent: <id> @ <host>
objective: o2
phase: implementation
delivery: d4
expected: <child-id> / c4
events: review_ready|research_needed|blocked|decision_required
review: pending | <review-thread-id> | rules:<AGENTS-and-skill-paths>
next: route review or dependency
children: <id> implementation active requested_model:gpt-5.6-sol requested_reasoning:medium attestation:requested
accepted: c3
outbox: send:d4 -> <child-id> committed
last: o1 done - <one-line summary>
```
Allow only events for the current role and phase. After approval, a finalize
delivery expects only `completed` or `blocked`. Each child owns its file. The
runtime-generated `<codex_delegation>.source_thread_id` identifies the callback
sender; sender identity never comes from the child payload. Persist review
scope, acceptance, `AGENTS.md`, and skill paths once; reference child evidence
instead of copying it.

## Dispatch

Only an explicit user request for visible delegation authorizes creation.
The model lanes and saved-project `local` environment are the user's explicit
creation policy for this skill.
Persist the creation token as `committed`, call `list_projects`, and require one
saved project matching working directory plus parent host ID; block on zero or
multiple matches. Call `create_thread(prompt=<payload>, model=<requested-model>,
thinking=<requested-reasoning>, target={type:"project",
projectId:<matched-project-id>, environment:{type:"local"}})`. Never use a
worktree, `fork_thread`, polling, or handshake.

```text
role: implementation|research|review
lane: locator|scout|worker|cross-layer|smart|review
requested_model: <exact create_thread model argument>
requested_reasoning: <exact create_thread thinking argument>
settings_attestation: requested
creation: <persisted-creation-token>
parent: <parent-thread-id> @ <parent-host-id>
state: <root>/<p>/children/<your-thread-id>.md
start: create state; persist this contract and current delivery before work
delivery: d1
callback: c1
scope: <bounded responsibility>
writes: <exact paths or none>
acceptance: <measurable outcome>
rules: <AGENTS.md and task-domain skills; exclude control/orchestrator skills>
artifact: <exact detailed-report path or none>
finish: <allowed event>; persist event/result/brief refs/outbox; send parent once {event,delivery,callback,summary,evidence:"child-state",next[,kind]}; blocked kind=recoverable|external; record sent|rejected|ambiguous; final=summary+state; create no tasks
```

Before creation, persist the lane plus the exact `model` and `thinking` arguments,
then pass the same values to `create_thread` and the task payload. The child
retains them in its state and resolves its ID from runtime metadata or one
`list_threads(query=<creation-token>)` snapshot with exactly one matching task;
zero or multiple matches block. It then creates its file and starts. Record
returned child and host IDs beside those launch arguments, mark creation sent,
make one immediate `wait_threads(targets=[{threadId:<id>,hostId:<host>}],
timeoutMs=0)` snapshot, emit
`::created-thread{threadId="<id>"}`, and end without waiting for completion.
Include the creation token verbatim in the initial prompt and invoke
`create_thread` at most once for that token. An error, timeout, or missing or
malformed receipt is `ambiguous`, never proof that no task was created. Persist
that state. On this and each later external activation, take one read-only
`list_threads(query=<creation-token>)` snapshot and `read_thread` its matches;
adopt exactly one. Zero matches remain ambiguous and multiple matches block.
Never retry creation or mint a replacement token for the same assignment.

Use `lane` to select the requested model and reasoning below, but never treat the
lane, UI, or a default as proof of effective runtime settings. The persisted
values prove only what the parent requested.
Change `settings_attestation` to `runtime-verified` only when a runtime receipt
or inspection explicitly reports both fields and persist that evidence;
otherwise keep `requested` and never claim the effective configuration.
Follow-ups contain only:

```text
delivery: d2 supersedes d1
callback: c2
change: <new instruction>
```

The child abandons `d1`, persists `d2`, and continues. A superseded callback is
stale and causes no acceptance, transition, or redispatch.

## Model Lanes

Set every created visible task explicitly; follow-ups keep its persisted model
and reasoning as well as its lane:

- Locator, mechanical and read-only: `gpt-5.6-luna` / `medium`.
- Scout, technical and read-only: `gpt-5.6-sol` / `low`.
- Worker, fixed contract and known pattern: `gpt-5.6-sol` / `medium`.
- Cross-layer Worker with resolved contract: `gpt-5.6-sol` / `medium`.
- Smart worker, durable research, independent review, or high-risk implementation:
  `gpt-5.6-sol` / `high`.
- Critical review or hard bounded retry after verified Sol high failure:
  `gpt-5.6-sol` / `xhigh`; rescope first.

Use Luna only for mechanical read-only or deterministic disposable work; any
interpretation routes to Sol. Max requires explicit user choice. Never compensate
for oversized scope with model effort; split first.

## Callback

Persist first, then send once:

```json
{"event":"review_ready","delivery":"d2","callback":"c2",
 "summary":"Implementation and checks ready for review",
 "evidence":"child-state","next":"review"}
```

Use `send_message_to_thread(threadId=<parent-id>, hostId=<parent-host>,
prompt=<json>)` without overrides. Known events: `review_ready`, `review_approved`, `changes_requested`, `completed`, `research_needed`, `research_completed`, `blocked`, `decision_required`, and requested `compact_recovered`.

Before mutation, require every shown string field, then validate trusted sender,
current delivery, expected callback, and the transition's allowed events.
`blocked` additionally requires `kind` equal to `recoverable` or `external`; use
`decision_required` for choices. Accept each callback once. Evidence stays in
the child file/artifacts. Local final is only a summary plus state path.

Persist every outbox row as `committed`; after the single attempt mark it
`sent`, `rejected:<error>`, or `ambiguous`. On error, reconcile once with
`read_thread`; never retry. On the parent's next external activation, it may
record `accepted_from_state` only when the expected task's final points to its
exact state file and that file matches the expected delivery, callback, allowed
event, and a rejected or ambiguous outbox. Otherwise block; never invent a
wrapper callback or ask the child to resend.

## Routing And Review

```text
implementation -> review_ready -> visible reviewer
                     changes -> same implementation -> same reviewer
                    approved -> implementation finalizes -> completed
```

Create an independent, read-only, project-local reviewer only for `review_ready`,
explicit requests, or risky/ambiguous results. Accept routine
`research_completed`, inventories, trackers, and deterministic evidence without review.
Reference durable state, not chat. The reviewer sends one minimal callback.
Return changes to and finalize through the same tasks; accept `completed` only
after approval.

Route `research_needed` only for bounded questions. Stop when evidence answers
the decision; exhaustive audits require explicit scope; research never implements.

## Visible Task Ownership

Children return `research_needed` and the parent routes the reusable visible
researcher. Assign non-overlapping ownership, applicable `AGENTS.md`, and skill
paths. Use `$crabbox` when configured and usable unless the user opts out; the
implementing child owns its runs.

## Closure And Recovery

After accepted result and planned reuse end, persist the outbox entry, call
`set_thread_archived(archived=true, threadId=<child-id>, hostId=<host-id>)`, and
record its receipt. At objective closure, remove only `active`, set the cursor
idle, and retain one summary.

`SessionStart(compact)` immediately injects one path before resumed work:
parent -> `<p>/control.md`; every visible child, including review -> its child
file. Thus the parent recovers the review recipe and reviewer identity. Missing
state for a declared or marked task, or duplicate mappings, injects a blocker;
unrelated tasks stay silent. Reread first.
