#!/usr/bin/env bash
# Builds the PDFs in pdf/ from the Markdown sources.
# Requires: pandoc, wkhtmltopdf. Run from anywhere: ./tools/build-pdf.sh
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/pdf"
TMP="$(mktemp -d)"
mkdir -p "$OUT"

build() {   # build <output name> <title> <markdown files...>
    local name="$1" title="$2"; shift 2
    local parts=()
    local first=1
    for f in "$@"; do
        if [ $first -eq 0 ]; then parts+=("$ROOT/tools/pagebreak.md"); fi
        parts+=("$f"); first=0
    done
    pandoc "${parts[@]}" -f gfm -t html5 -s --metadata title="$title" \
        --css "$ROOT/tools/pdf.css" --embed-resources -o "$TMP/$name.html"
    # pandoc's standalone template prints the title as a header: remove it, the H1 is enough
    sed -i 's#<header id="title-block-header">.*</header>##; /<header id="title-block-header">/,/<\/header>/d' "$TMP/$name.html"
    wkhtmltopdf --quiet --enable-local-file-access -s A4 -T 15mm -B 15mm -L 10mm -R 10mm \
        "$TMP/$name.html" "$OUT/$name.pdf"
    echo "built pdf/$name.pdf"
}

cd "$ROOT"
build 00-setup         "PHP course — Setup"         00-setup/README.md
build 01-introduction  "PHP course — 01 Introduction" 01-introduction/README.md 01-introduction/exercises.md
build 02-procedural    "PHP course — 02 Procedural"   02-procedural/README.md 02-procedural/exercises.md
build 03-oop           "PHP course — 03 OOP"          03-oop/README.md 03-oop/exercises.md
build 04-web           "PHP course — 04 Web"          04-web/README.md 04-web/exercises.md
build 05-database      "PHP course — 05 Database"     05-database/README.md 05-database/exercises.md
build 06-mysql         "PHP course — 06 MySQL"        06-mysql/README.md 06-mysql/exercises.md
build 07-final-project "PHP course — 07 Final project" 07-final-project/README.md
build cheatsheet       "PHP cheatsheet"               cheatsheet.md
build cheatsheet-modern-php "Modern PHP cheatsheet"  cheatsheet-modern-php.md

rm -rf "$TMP"
