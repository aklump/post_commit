#!/bin/bash
#
# @file
# An example script to use in post commit.

# This is the location on the server for drush.  Not sure why, but we have to specify it as an absolute path.
drush=/usr/local/bin/drush

source="${BASH_SOURCE[0]}"
while [ -h "$source" ]; do # resolve $source until the file is no longer a symlink
  dir="$( cd -P "$( dirname "$source" )" && pwd )"
  source="$(readlink "$source")"
  [[ $source != /* ]] && source="$dir/$source" # if $source was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
root="$( cd -P "$( dirname "$source" )" && pwd )"

(cd $root/.. && git pull)
(cd $root/../web && $drush cc all)
