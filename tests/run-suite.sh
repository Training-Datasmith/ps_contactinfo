#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
PHP_IMAGE="php@sha256:6ce95208609dc66df163ab936c970b3b34cd901b85c747102c5999f08ade9143"
PHAR="/tmp/phpunit-5.7.27.phar"
PHAR_ASC="/tmp/phpunit-5.7.27.phar.asc"
PHAR_SHA256="f0300a13f2653bee10b0ce24dbc8d94e65dc9be8966d822b9e455b6b294be16e"
GPG_KEY="D8406D0D82947747293778314AA394086372C20A"
GPG_KEYRING="/tmp/phpunit-5.7.27-signers.gpg"

ensure_phpunit_phar() {
  if [[ ! -f "$PHAR" || ! -f "$PHAR_ASC" ]]; then
    curl -fsSL -o "$PHAR" https://phar.phpunit.de/phpunit-5.7.27.phar
    curl -fsSL -o "$PHAR_ASC" https://phar.phpunit.de/phpunit-5.7.27.phar.asc
  fi

  local gpg_home
  gpg_home=$(mktemp -d)
  gpg --homedir "$gpg_home" --batch --keyserver hkps://keyserver.ubuntu.com --recv-keys "$GPG_KEY" >/dev/null 2>&1 || \
    gpg --homedir "$gpg_home" --batch --keyserver hkps://keys.openpgp.org --recv-keys "$GPG_KEY" >/dev/null 2>&1
  gpg --homedir "$gpg_home" --batch --export "$GPG_KEY" > "$GPG_KEYRING"
  rm -rf "$gpg_home"

  gpgv --keyring "$GPG_KEYRING" "$PHAR_ASC" "$PHAR"
  echo "${PHAR_SHA256}  ${PHAR}" | sha256sum -c -
}

run_mode="${1:-xml}"

ensure_phpunit_phar

DOCKER="${DOCKER:-docker}"
if ! $DOCKER info >/dev/null 2>&1; then
  DOCKER="sudo docker"
fi

$DOCKER run --rm \
  -v "$ROOT:/app" \
  -v "$PHAR:/usr/local/bin/phpunit.phar:ro" \
  -w /app/tests \
  "$PHP_IMAGE" \
  php -d display_errors=1 -d error_reporting=32767 \
  /app/tests/docker-runner.php "$run_mode"
