#!/usr/bin/env bash
# Builds wp-theme/dist/thedailydrip.zip from the static site (styles.css, main.js, img, fonts)
# + the PHP templates in wp-theme/src. Upload the zip in WordPress: Appearance > Themes > Add new > Upload.
set -euo pipefail
cd "$(dirname "$0")"
ROOT=..
OUT=dist/thedailydrip
rm -rf dist && mkdir -p "$OUT/assets" "$OUT/inc"
cat src/style-header.css "$ROOT/styles.css" src/wp-extra.css > "$OUT/style.css"
cp "$ROOT/main.js" "$OUT/assets/main.js"
cp -r "$ROOT/fonts" "$OUT/"
# Only the photos the theme uses (training / beans photos stay out until those pages return)
mkdir -p "$OUT/img"
for f in logo.svg email-logo.png lunch-bg hero-bg hero-bg-sm hero-card mission-1 mission-2 mission-3 statement-bg statement-bg-sm menu-bg menu-bg-sm company-bg company-bg-sm; do
  case "$f" in *.svg|*.png) cp "$ROOT/img/$f" "$OUT/img/";; *) cp "$ROOT/img/$f.webp" "$OUT/img/";; esac
done
cp src/*.php "$OUT/" && cp src/inc/*.php "$OUT/inc/"
[ -f src/screenshot.jpg ] && cp src/screenshot.jpg "$OUT/"
(cd dist && zip -rqX thedailydrip.zip thedailydrip)
echo "Built $(pwd)/dist/thedailydrip.zip ($(du -h dist/thedailydrip.zip | cut -f1))"
