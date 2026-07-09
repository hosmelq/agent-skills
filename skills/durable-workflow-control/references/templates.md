# Durable Workflow Control Templates

Copy only the sections needed for the current tier into repo-local files under
`.agents/workflows/durable-workflow-control/<objective-slug>/`. Keep the primary
control compact; put evidence, source notes, action/observation rows, and
history in support artifacts.

## Primary Control File

```markdown
# <Workflow Name> - Control

This file is the primary control and cursor. Keep it compact. Move evidence, source notes, command logs, action/observation rows, and historical tick detail into linked support artifacts.

## Purpose
- Outcome: <concrete outcome>
- Scope: <included work>
- Exclusions: <read-only/no-goal/no-internet/no-subagents/etc>

## Requested Gate
- Gate: discuss | artifact_only | initialize_and_wait | initialize_and_start | resume | reset | audit | improve
- Gate evidence: <explicit loop/queue/durable continuation request, existing control artifact, or n/a>
- Stop at: <requested checkpoint | after Q<N> | closure | named checkpoint>

## Artifact Links
- Primary control: .agents/workflows/durable-workflow-control/<objective-slug>/control.md
- Evidence: .agents/workflows/durable-workflow-control/<objective-slug>/evidence.md | n/a
- History: .agents/workflows/durable-workflow-control/<objective-slug>/history.md | n/a
- Sources: .agents/workflows/durable-workflow-control/<objective-slug>/sources.md | n/a
- Work: .agents/workflows/durable-workflow-control/<objective-slug>/work.md | n/a
- Alternatives: .agents/workflows/durable-workflow-control/<objective-slug>/evidence.md#alternatives | n/a
- Final synthesis/handoff: .agents/workflows/durable-workflow-control/<objective-slug>/evidence.md#final-synthesis | n/a
- Goal: <thread/id or n/a; only if requested/required>

## Current State
- Current tick: Q0.
- Workflow status: pending | in_progress | complete | blocked: <plain reason>.
- Stop-state precheck: continue | stop: <plain reason>.
- Next action: <exact next action>.
- Progress locator: <control file/progress comment/PR/branch/goal>.
- Last verified revision/state: <revision/id/timestamp or n/a>.
- Claim owner: <agent/thread/id or n/a>.
- Final verdict: pending | ready | ready_with_constraints | ready_except_named_decisions | partial_discovery_ready_for_next_pass | blocked_by_specific_gap | blocked_by_missing_evidence.

## Budget And Permissions
- Autonomy level: 1 suggest | 2 draft | 3 apply with approval | 4 apply granted scope
- Max ticks: <n>
- Max source reads: <n or n/a>
- Max subagents: <n or n/a>
- Max verifier-only ticks after last output change: 0
- Allowed writes: <paths/artifacts>
- Allowed commands/tools: <allowlist>
- Stop-on-budget: <behavior>

## Queue
| Tick | Slice | Claim owner | Expected revision | Verifier | Status | Evidence | Next action |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Q0 | Initialize artifacts and state | <agent/thread> | <revision/timestamp> | readback | pending | n/a | <next> |
| Q1 | <first slice> | n/a | n/a | <checker> | pending | <link> | <next> |

## Blockers
- None.

## Closure Checklist
- [ ] Queue/work rows done, deferred, excluded, or blocked with evidence.
- [ ] Required support artifacts populated.
- [ ] Coverage claim reconciled where applicable.
- [ ] Required verification recorded.
- [ ] Human checkpoints resolved or deferred with reason.
- [ ] Final synthesis maps claims to evidence.
- [ ] Final verdict recorded.
- [ ] Goal complete or n/a.
```

## Optional Primary Sections

Add these only when they determine the next action.

```markdown
## Loop Model
- Loop tier: Minimal | Standard | Research/design | Improvement
- Inner-loop tick: <what one tick does>
- Outer-loop trigger: <explicit command, N reviews, release boundary, or n/a>
- Outer-loop output: no-op report | control proposal | patch | PR | n/a

## Checkpoints
- Human approval before: <publish/merge/send/delete/db/permissions/skill/memory/template/product decision>
- Idempotency notes: <what may rerun after resume>
- Invariant surfaces: <markers/status vocabulary/required sections/permission boundaries>
```

## Support Artifact Header

```markdown
# <Workflow Name> - <Evidence | History | Sources | Work>

## Role
- Primary control: .agents/workflows/durable-workflow-control/<objective-slug>/control.md
- Artifact role: evidence | history | sources | work
- Cursor authority: none. The primary control decides current state and next action.
- Update rule: append or summarize detail here, then write only the current operational implication back to the primary control.

## Current Summary
<Compact summary relevant to the primary control.>
```

## Tier Modules

### Standard Work Matrix

```markdown
# <Workflow Name> - Work Matrix

## Matrix Rules
- Row ids are stable.
- Task/source/acceptance/verifier fields are immutable after Q0 unless reset scope is explicit.
- Workers update only claim owner, expected revision, status, evidence, verification, blocker, and next action.
- A row cannot be done without verifier evidence. Workflow `complete` belongs in the primary control closure state.
- Claim owner and expected revision are required before write-capable work in shared worktrees.

## Rows
| Id | Slice/source | Acceptance/verifier | Claim owner | Expected revision | Status | Evidence | Verification | Blocker/decision | Next action |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| W-001 | <task/source> | <test/source/reviewer/rubric> | n/a | n/a | pending | <link> | <result> | <none> | <next> |
```

### Research Source Ledger

```markdown
### S-<NNN> - <Source title>
- Source/path: <link, id, path, or description>
- Source type: primary | official docs | repo | social | framing | local artifact
- Access status: read | attempted_inaccessible | withheld | superseded | not_needed
- Read method: browser | local file | command | connector | requested tool | human-provided artifact
- Read timestamp: <date/time>
- Parent source / necessity: <parent id and why followed, or n/a>
- Key evidence: <short bullets>
- Evidence quality: high | medium | low
- Protocol implication: <what changes or no-op>
- Notes location: <section/link>
```

### Source Notes

```markdown
### W<NN> - <Title>
- Source: <link, id, path, or description>
- Access status: <status>
- Summary: <what the source actually says>
- Useful concepts: <context/state/verifier/outer-loop/etc>
- Exclusions: <mechanics not imported>
- Rewrite implication: <SKILL.md/protocol/templates/no-op>
```

### Coverage Contract

```markdown
## Coverage Contract
- Coverage claim to prove: <what the final answer is allowed to claim, or n/a>
- Universe: <repo area/source set/diff/thread/data set/etc>
- Method: targeted_search | grouped_review | sampled_review | full_read | deterministic_test | external_verifier | human_review | mixed
- Reproducible evidence: <commands/queries/source ids/counts/group notes>
- Known limits/blind spots: <none or named>
- Coverage checker: <rubric/test/source reconciliation/human review/etc>
- Allowed verdicts: ready | ready_with_constraints | ready_except_named_decisions | partial_discovery_ready_for_next_pass | blocked_by_specific_gap | blocked_by_missing_evidence
```

### Evidence Row

```markdown
### E-<NNN> - <Finding>
- Claim: <finding>
- Evidence source(s): <source ids/paths/test outputs>
- Coverage method: targeted_search | grouped_review | sampled_review | full_read | deterministic_test | external_verifier | human_review | mixed | n/a
- Evidence quality: high | medium | low
- Action/observation evidence: <AO ids or n/a>
- Contradictions: <none or source ids>
- Protocol/template implication: <rule/change/no-op>
- Status: done
```

### Alternatives

```markdown
## Alternatives
| Id | Option | Evidence | Tradeoff | Decision | Reason |
| --- | --- | --- | --- | --- | --- |
| A-001 | <option> | <source/evidence ids> | <cost/risk/benefit> | selected/rejected/deferred | <reason> |
```

### Final Synthesis

```markdown
## Final Synthesis
- Claim: <final claim>
- Evidence map: <source/evidence/checker ids>
- Coverage limit: <none or named>
- Alternatives considered: <alternative ids>
- Decision/handoff: <final output or next owner/action>
```

### Action/Observation Row

Use this only when attempts can be invalid, illegal, unsuccessful, or successful in ways that should shape later ticks.

```markdown
### AO-<NNN> - <Tick/action>
- Tick: Q<N>
- Proposed action: continue_slice | split_slice | revise_output | revoke_claim | rerun_checker | record_blocker | request_approval | explore_alternative | stop_with_verdict | <domain-specific action>
- Validity precheck: pass | fail, with reason
- Precheck detail: <schema/id/source/scope/permission/cursor/side-effect result>
- Checker: <test/source/rubric/human/eval/tool or n/a>
- Checker guarantee/limits: <what this checker proves and known blind spots>
- Observation: invalid | illegal | failed | succeeded | stop_requested | stop_accepted | stop_rejected | iteration_limit_reached
- Result detail: <improvement/regression/no useful change/speedup/slowdown/evidence delta/checker result or n/a>
- Feedback detail: <exact reason, metric, missing evidence, error, blocker, or repair hint>
- Strategy update: <what changes next because of this observation>
- Quit attempt: <n/a or N>
- Best-so-far / accepted output: <artifact/link or n/a>
- Next legal action: <exact action>
- Evidence location: <link/section>
```

### Tick Entry

```markdown
### Tick Q<N> - <Name>
- Run identity: <run/thread/trace/progress locator>
- Status: pending | in_progress | done | blocked: <plain reason>
- Slice: <one bounded unit>
- Sources read: <links/files>
- Output: <artifact/path/patch/summary>
- Checker: <test/source/rubric/human/eval>
- Checker guarantee/limits: <what this checker proves and known blind spots>
- Observation: <observation or n/a>
- Result detail: <result or n/a>
- Evidence recorded: <artifact/section>
- Blockers/exclusions: <none or named>
- Next action: <exact next action>
```

### Guardrail Or Approval Row

```markdown
### G-<NNN> - <Guardrail/approval>
- Type: input | action_precheck | tool | output | human_approval
- Mode: blocking | parallel | post_output
- Condition: <when it trips>
- Protected side effect: none | files | db | send | delete | publish | credentials | memory | skill
- Tool/call id: <id or n/a>
- Arguments/data involved: <summary, no secrets>
- Decision: pending | approved | rejected | not_applicable
- Resume state: <thread id/checkpoint/progress locator>
- Idempotency note: <what may have happened before pause>
- Evidence location: <link/section>
```

### Human Review Evidence Row

```markdown
### HR-<NNN> - <Reviewed output>
- Initial output: <artifact/link/version>
- Source trace: <sources/checker evidence/prompt version>
- Output marker/version: <marker/version/run id or n/a>
- Run identity: <run/thread/trace/progress locator>
- Human outcome: accepted_unchanged | edited_accepted | rejected_deleted | pending | conflicting
- Final approved output: <artifact/link or n/a>
- Diff artifact/link or exact diff: <artifact/link/exact diff or n/a>
- Diff summary: <what changed>
- Classification(s): style | missing_context | wrong_source | prompt_gap | checker_gap | unsupported_commitment | product_decision | human_only_judgment
- Signal strength: strong | moderate | weak | no_signal
- Lesson candidate: <candidate or none>
- Approved lesson: <approved lesson/store or n/a>
```

### Outer Improvement Loop

```markdown
# <Skill/Loop> Outer Improvement

## Source Window
- Trigger: explicit command | N reviewed runs | release boundary
- Run window: <ids/date range/count>
- Required evidence threshold: <minimum before edit>
- Scope boundary: <which inner skill/template/harness is eligible for changes>
- Invariant surfaces: <markers/status vocabulary/required sections/permission boundaries>

## Inner Run Records
| Run | Version | Output marker | Outcome | Feedback signal | Signal strength | Evidence | Verdict |
| --- | --- | --- | --- | --- | --- | --- | --- |
| R-001 | <version> | <marker/trace> | <outcome> | <signal> | strong/moderate/weak/no_signal | <link> | correct/incorrect/no_signal |

## Candidate Lessons
| Candidate | Evidence | Signal strength | Decision | Reason |
| --- | --- | --- | --- | --- |
| <lesson> | <run ids> | strong | promote | <why> |

## Proposed Output
- Result: no-op report | control proposal | patch | PR
- Files to change: <paths or n/a>
- Smallest useful change: <summary>
- Human review required before: <merge/apply/memory write/etc>
```

## Subagent Validation Row

```markdown
### SA-<NNN> - <Agent/scenario>
- Prompt scope: <realistic task and raw artifacts>
- Files/sources reviewed: <exact paths/links>
- Recommendation: <summary>
- Accepted findings: <list>
- Rejected findings: <list with reason>
- Resolution: <edit/follow-up/no change>
- Closure authority: none; reconciled against primary artifacts
```

## Goal Text

```text
Objective: <concrete outcome>.
Scope: <included/excluded>.
Queue/control: <verified link or n/a>.
Supporting artifacts: <verified links with roles or n/a>.
Source requirements: <local/external/code/docs/human-provided artifacts>.
Rules: follow the queue when one exists; do not write final synthesis before
source ledger/evidence are populated; do not mark complete from memory alone;
record blockers/exclusions and verification.
Completion criteria: <explicit checklist>.
Allowed verdicts: <evidence-bounded verdicts>.
```

## Completion Audit

```markdown
## Completion Audit
| Requirement | Evidence needed | Evidence inspected | Status | Notes |
| --- | --- | --- | --- | --- |
| <requirement> | <file/test/artifact> | <actual evidence> | proven/contradicted/incomplete/weak/missing | <notes> |

## Coverage Verdict
- Coverage claim: <claim or n/a>
- Universe: <scope>
- Method actually used: <method>
- Checker result: proven | partial | contradicted | missing
- Verdict limit: ready | ready_with_constraints | ready_except_named_decisions | partial_discovery_ready_for_next_pass | blocked_by_specific_gap | blocked_by_missing_evidence

## Final Verdict
- Verdict: ready | ready_with_constraints | ready_except_named_decisions | partial_discovery_ready_for_next_pass | blocked_by_specific_gap | blocked_by_missing_evidence
- Constraints: <none or named>
- Remaining blockers/deferred decisions: <none or named>
```
