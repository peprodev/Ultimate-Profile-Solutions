#!/usr/bin/env bash
# Builds a clean, installable release zip: dist/peprodev-ups-<version>.zip
# (single top-level folder "peprodev-ups/", no dev files). The same tree is
# what goes to WordPress.org SVN trunk / tags/<version>.
set -euo pipefail
cd "$(dirname "$0")/.."

ver=$(grep -m1 -E '^Version:' peprodev-ups.php | sed -E 's/^Version:[[:space:]]*//' | tr -d '[:space:]')
stable=$(grep -m1 -E '^Stable tag:' readme.txt | sed -E 's/^Stable tag:[[:space:]]*//' | tr -d '[:space:]')
if [ "$ver" != "$stable" ]; then
  echo "Version mismatch: header $ver vs readme Stable tag $stable" >&2
  exit 1
fi

tmp=$(mktemp -d)
trap 'rm -rf "$tmp"' EXIT
mkdir -p "$tmp/peprodev-ups" dist
rsync -a ./ "$tmp/peprodev-ups/" \
  --exclude='.git' --exclude='.gitignore' --exclude='.github' --exclude='.DS_Store' \
  --exclude='bin' --exclude='dist' --exclude='node_modules' --exclude='*.map' \
  --exclude='README.md' --exclude='jsconfig.json' --exclude='.idea' --exclude='.vscode' \
  --exclude='.claude' --exclude='CLAUDE.md' --exclude='AGENTS.md'

out="dist/peprodev-ups-$ver.zip"
rm -f "$out"
(cd "$tmp" && zip -rqX "$OLDPWD/$out" peprodev-ups)
echo "$out ($(unzip -Z1 "$out" | wc -l | tr -d ' ') entries)"
