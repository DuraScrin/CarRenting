#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd -- "$SCRIPT_DIR/.." && pwd)"
DIST_DIR="$SCRIPT_DIR/dist"
FTP_DIR="$DIST_DIR/public_html-ftp"

rm -rf "$FTP_DIR"
mkdir -p "$FTP_DIR"

cp -a "$PROJECT_ROOT/public_html/." "$FTP_DIR/"

echo "Prepared FTP folder: $FTP_DIR"
echo "Upload all files/folders inside this directory to your hosting public_html root."
