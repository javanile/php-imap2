#!/usr/bin/env bash

## Load environment for local testing
[ -f .env ] && source .env

## ======
## Google
## ======

## Refresh the access token
access_token=$(curl -s \
  -X POST \
  -d "client_id=$GOOGLE_CLIENT_ID" \
  -d "client_secret=$GOOGLE_CLIENT_SECRET" \
  -d "refresh_token=$GOOGLE_REFRESH_TOKEN" \
  -d 'grant_type=refresh_token' \
  https://accounts.google.com/o/oauth2/token | grep "access_token" | cut -d'"' -f4)

## Store newly access token
gh secret set GOOGLE_ACCESS_TOKEN --body $access_token --repo javanile/php-imap2
