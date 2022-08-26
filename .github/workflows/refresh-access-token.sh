#!/usr/bin/env bash

#curl \
#  -X GET \
#  -H "Accept: application/vnd.github+json" \
#  -H "Authorization: token $GITHUB_TOKEN" \
#  https://api.github.com/repos/javanile/php-imap2/actions/secrets/GOOGLE_ACCESS_TOKEN

gh secret set GOOGLE_ACCESS_TOKEN --org javanile --repos php-imap2 --body HELLO

