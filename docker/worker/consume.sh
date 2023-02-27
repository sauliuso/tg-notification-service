#!/bin/sh
/srv/app/bin/console messenger:consume async --limit=10 >&1;