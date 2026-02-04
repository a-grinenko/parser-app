#!/bin/bash

set -e

echo "Running Composer install..."
composer install --prefer-dist --no-progress --no-interaction

exec "$@"
