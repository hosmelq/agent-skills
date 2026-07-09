#!/usr/bin/env python3
"""Inject a compacted visible task's exact orchestrator state path."""
import json, re, sys
from pathlib import Path

SAFE_ID = re.compile(r"^[A-Za-z0-9][A-Za-z0-9._-]*$")


def find_root(cwd):
    for directory in (cwd, *cwd.parents):
        root = directory / ".agents/workflows/codex-thread-orchestrator"
        if root.is_dir():
            return root


def mappings(root, thread_id):
    if not SAFE_ID.fullmatch(thread_id):
        return []
    mapped = []
    parent = root / thread_id
    if (parent / "parent").is_file():
        mapped.append(parent / "control.md")
    for child in root.glob(f"*/children/{thread_id}.md"):
        if (child.parents[1] / "parent").is_file():
            mapped.append(child)
    for control in root.glob("*/control.md"):
        try:
            declared = any(thread_id in line.split()
                           for line in control.read_text().splitlines()
                           if line.startswith("children:"))
        except OSError:
            declared = False
        if declared and (control.parent / "parent").is_file():
            mapped.append(control.parent / "children" / f"{thread_id}.md")
    return sorted(set(mapped))


def recovery_context(root, thread_id):
    mapped = mappings(root, thread_id)
    if len(mapped) == 1 and mapped[0].is_file():
        return (f"Compaction recovery: re-read {mapped[0]} before reasoning or tools; "
                "it is the durable source of truth, not chat history.")
    if mapped:
        reason = "multiple mappings" if len(mapped) > 1 else "mapped state is missing"
        return f"Compaction recovery blocker for {thread_id}: {reason}. Stop and report it."


def main():
    try:
        payload = json.load(sys.stdin)
        if (payload.get("hook_event_name"), payload.get("source")) != ("SessionStart", "compact"):
            return 0
        thread_id, cwd = payload.get("session_id"), payload.get("cwd")
        if not isinstance(thread_id, str) or not isinstance(cwd, str):
            return 0
        root = find_root(Path(cwd).resolve())
        context = recovery_context(root, thread_id) if root else None
        if context:
            json.dump({"hookSpecificOutput": {"hookEventName": "SessionStart",
                      "additionalContext": context}}, sys.stdout, separators=(",", ":"))
            sys.stdout.write("\n")
    except (AttributeError, OSError, TypeError, ValueError):
        pass
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
