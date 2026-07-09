import json, subprocess, tempfile, unittest
from pathlib import Path

SKILL = Path(__file__).resolve().parents[1]
SCRIPT = SKILL / "scripts/compact_rehydration.py"


class RecoveryTest(unittest.TestCase):
    def setUp(self):
        self.temp = tempfile.TemporaryDirectory()
        self.cwd = Path(self.temp.name)
        self.root = self.cwd / ".agents/workflows/codex-thread-orchestrator"

    def tearDown(self): self.temp.cleanup()

    def workflow(self, parent="parent", active=True, marked=True):
        path = self.root / parent
        path.mkdir(parents=True)
        (path / "control.md").write_text("parent index\n")
        if marked: (path / "parent").touch()
        if active: (path / "active").write_text("o1\n")
        return path

    def child(self, flow, child="child"):
        path = flow / "children" / f"{child}.md"
        path.parent.mkdir()
        path.write_text("child state\n")
        return path

    def invoke(self, thread, event="SessionStart", source="compact", cwd=None):
        payload = {"session_id": thread, "cwd": str(cwd or self.cwd),
                   "hook_event_name": event, "source": source}
        return subprocess.run(["python3", str(SCRIPT)], input=json.dumps(payload),
                              text=True, capture_output=True, check=False).stdout

    def context(self, output):
        return json.loads(output)["hookSpecificOutput"]["additionalContext"]

    def test_parent_child_idle_and_nested_recover_immediately(self):
        active = self.workflow()
        child = self.child(active)
        idle = self.workflow("idle", active=False) / "control.md"
        nested = self.cwd / "a/b"; nested.mkdir(parents=True)
        for thread, path, cwd in (("parent", active / "control.md", nested),
                                  ("child", child, None), ("idle", idle, None)):
            self.assertIn(str(path), self.context(self.invoke(thread, cwd=cwd)))

    def test_unrelated_bad_and_subagent_events_are_silent(self):
        self.workflow(marked=False)
        for thread in ("parent", "unknown", "../parent"):
            self.assertEqual("", self.invoke(thread))
        self.assertEqual("", self.invoke("parent", source="resume"))
        self.assertEqual("", self.invoke("parent", event="SubagentStart"))

    def test_duplicate_and_missing_mapping_block(self):
        self.child(self.workflow("one"), "shared")
        self.child(self.workflow("two"), "shared")
        self.assertIn("multiple mappings", self.context(self.invoke("shared")))
        flow = self.workflow("loss")
        state = self.child(flow, "lost")
        (flow / "control.md").write_text("children: lost implementation active\n")
        state.unlink()
        self.assertIn("mapped state is missing", self.context(self.invoke("lost")))
        self.child(self.workflow("present"), "hybrid")
        missing = self.workflow("missing")
        (missing / "control.md").write_text("children: hybrid implementation active\n")
        self.assertIn("multiple mappings", self.context(self.invoke("hybrid")))

    def test_hook_configuration(self):
        hooks = json.loads((SKILL / "hooks/user-hooks.json").read_text())["hooks"]
        self.assertEqual(["SessionStart"], list(hooks))
        entry = hooks["SessionStart"][0]
        self.assertEqual("compact", entry["matcher"])
        self.assertEqual([{"type": "command", "command":
                          'python3 "$HOME/.agents/skills/codex-thread-orchestrator/'
                          'scripts/compact_rehydration.py"'}], entry["hooks"])

if __name__ == "__main__":
    unittest.main()
