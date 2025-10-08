#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# project root
cd "$(dirname "$DIR")"

APP_ENV="test" php bin/console doctrine:database:create --no-interaction --if-not-exists
APP_ENV="test" php bin/console doctrine:migrations:migrate --no-interaction
APP_ENV="test" php bin/console doctrine:schema:update --complete --force --no-interaction

set -x

APP_ENV="test" php vendor/bin/behat "$@"
