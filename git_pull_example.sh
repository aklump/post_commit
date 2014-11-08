#!/bin/bash
# 
# @file
# An example script to use in post commit.

# Move into the git repo and do a rebase from origin.
cd /var/www/mysite.com/public_html && git pull --rebase