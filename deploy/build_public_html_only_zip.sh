#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd -- "$SCRIPT_DIR/.." && pwd)"
DIST_DIR="$SCRIPT_DIR/dist"
ARCHIVE_PATH="$DIST_DIR/duraride-public-html-only.zip"

mkdir -p "$DIST_DIR"
rm -f "$ARCHIVE_PATH"

(
  cd "$PROJECT_ROOT/public_html"
  zip -rq "$ARCHIVE_PATH" .
)

echo "Archive file: $ARCHIVE_PATH"
