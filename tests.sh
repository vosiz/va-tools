#!/usr/bin/env bash

# Runs the test suite in Docker on every supported PHP version, no PHP needed on the host
#   ./tests.sh                      whole suite
#   ./tests.sh --filter Collection  any PHPUnit arguments
#   PHP_VERSIONS=8.2 ./tests.sh     only chosen version(s)

cd "$(dirname "$0")"

PHP_VERSIONS="${PHP_VERSIONS:-7.4 8.2}"
NAME="$(basename "$PWD")-tests"
FAILED=""

run() {

    local image="$1"; shift
    docker run --rm -u "$(id -u):$(id -g)" -e HOME=/tmp -v "$PWD":/app -w /app "$image" "$@"
}

for version in $PHP_VERSIONS; do

    image="$NAME:$version"

    if ! docker image inspect "$image" > /dev/null 2>&1; then

        echo "Building $image (first run only)"
        docker build -q -t "$image" --build-arg PHP_VERSION="$version" tests > /dev/null || exit 1
    fi

    if [ ! -f vendor/autoload.php ] || [ composer.json -nt vendor/autoload.php ]; then

        echo "Installing dependencies"
        run "$image" composer update --no-interaction --no-progress --quiet || exit 1
        touch vendor/autoload.php
    fi

    echo
    echo "=== PHP $version ==="
    run "$image" vendor/bin/phpunit "$@" || FAILED="$FAILED $version"
done

echo
if [ -n "$FAILED" ]; then

    echo "FAILED on PHP:$FAILED"
    exit 1
fi

echo "PASSED on PHP: $PHP_VERSIONS"
