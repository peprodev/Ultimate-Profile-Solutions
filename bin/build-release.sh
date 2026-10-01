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

# Namespaced files must use global classes with a leading backslash (\PeproDevUPS_WPML::...),
# otherwise PHP looks for them in the file's namespace and fails at runtime ("Class ... not found").
bad=$(for f in $(grep -rlE '^namespace ' --include='*.php' . | grep -v '^./dist/'); do
  defined=$(grep -oE '^(final |abstract )?class [A-Za-z0-9_]+' "$f" | awk '{print $NF}' | paste -sd'|' -)
  grep -nE '(^|[^\\A-Za-z0-9_$>:])[A-Z][A-Za-z0-9_]+::' "$f" \
    | grep -vE '^[0-9]+:[[:space:]]*(\*|//|#|/\*)' \
    | grep -vE '(self|parent|static)::' \
    | { if [ -n "$defined" ]; then grep -vE "(^|[^A-Za-z0-9_])($defined)::"; else cat; fi; } \
    | sed "s#^#$f:#" || true
done)
if [ -n "$bad" ]; then
  echo "Unqualified global class in a namespaced file (add a leading backslash):" >&2
  echo "$bad" >&2
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
