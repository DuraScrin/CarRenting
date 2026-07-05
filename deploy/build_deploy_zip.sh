#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd -- "$SCRIPT_DIR/.." && pwd)"
DIST_DIR="$SCRIPT_DIR/dist"
PACKAGE_DIR="$DIST_DIR/duraride-package"
ARCHIVE_NAME="duraride-shared-hosting.zip"

rm -rf "$PACKAGE_DIR"
mkdir -p "$PACKAGE_DIR/public_html"

cp -a "$PROJECT_ROOT/public_html/." "$PACKAGE_DIR/public_html/"
cp -a "$PROJECT_ROOT/src" "$PACKAGE_DIR/src"
cp -a "$PROJECT_ROOT/database" "$PACKAGE_DIR/database"

if [[ -f "$PROJECT_ROOT/.env.example" ]]; then
  cp "$PROJECT_ROOT/.env.example" "$PACKAGE_DIR/.env.example"
fi

if [[ -f "$PROJECT_ROOT/composer.json" ]]; then
  cp "$PROJECT_ROOT/composer.json" "$PACKAGE_DIR/composer.json"
fi

if [[ -f "$PROJECT_ROOT/README.md" ]]; then
  cp "$PROJECT_ROOT/README.md" "$PACKAGE_DIR/README.md"
fi

rm -f "$DIST_DIR/$ARCHIVE_NAME"
(
  cd "$DIST_DIR"
  zip -rq "$ARCHIVE_NAME" "duraride-package"
)

echo "Package folder: $PACKAGE_DIR"
echo "Archive file: $DIST_DIR/$ARCHIVE_NAME"
