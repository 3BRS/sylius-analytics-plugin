#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

# Get current script directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# cd to plugin root
cd "$(dirname "$DIR")"

set -x

# Run Behat in plugin root using test env
APP_ENV="test" php vendor/bin/behat "$@"
