#!/usr/bin/env bash

bin/console survos:user:create  demo@example.com demo
bin/console survos:user:create admin@example.com admin --roles ROLE_ADMIN --roles ROLE_SUPER_ADMIN
