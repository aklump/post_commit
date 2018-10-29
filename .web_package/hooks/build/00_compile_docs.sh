#!/bin/bash
#
# @file Compiles Loft Docs.
#
! test -d "$7/docs" || rm -rf "$7/docs" || exit 1
cd "$7/documentation/" && ./core/compile.sh || exit 1
test -f "$7/docs/index.html" || exit 1

## Copy screenshot.
test -d "$7/images" || mkdir -p "$7/images"
cp "$7/documentation/source/images/screenshot.jpg" "$7/images/screenshot.jpg" || exit 1

## Auto commit the files generated as output.
git=$(type git >/dev/null 2>&1 && which git)
if test -d $7/.git && [ "$git" ]; then
    # Note to support symlinks, we should cd first (per git).
    (cd $7/docs && git add .)
    (cd $7 && git add README.md && git add images/screenshot.jpg)
fi

exit 0
