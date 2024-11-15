# Biblys

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

Biblys is an open-source web application designed to build online bookshops,
primarily used by French independent book publishers.

## Requirements

- PHP 8.1 or above
- MySQL 8
- Composer
- Git
- Yarn

## Install

See [INSTALL.MD](./INSTALL.md).

## Dev env with docker

```
$ docker-compose up -d
```

If docker image needs to be updated:

```
$ docker-compose up --build
```

## Build assets

In dev mode:

```console
composer build:watch
```

For production:

```console
composer build:prod
```

## Manage themes

Refresh a theme after local changes or download:

```console
composer theme:refresh
```

Get theme latest version from Github:

```console
composer theme:download
```

Download & refresh theme:

```console
composer theme:update
```

(DEV) Auto-refresh theme when a file is changed:

```console
composer theme:watch
```

## Run tests

```console
composer test
```

## Add AGPL headers to source code files

```shell
docker run -it -v ${PWD}:/src ghcr.io/b1nary-gr0up/nwa:main add \
  --copyright "Clément Latzarus" \
  --license agpl-3.0-only \
  --skip "vendor/**" \
  --skip "src/Model/Base/*.php" \
  --skip "src/Model/Map/*.php" \
  **/*.php
```

```shell
docker run -it -v ${PWD}:/src ghcr.io/b1nary-gr0up/nwa:main add \
  --copyright "Clément Latzarus" \
  --license agpl-3.0-only \
  assets/**/*.js \
  public/assets/js/*.js \
  public/common/js/*.js
```

```shell
docker run -it -v ${PWD}:/src ghcr.io/b1nary-gr0up/nwa:main add \
  --copyright "Clément Latzarus" \
  --license agpl-3.0-only \
  **/*.twig
```

```shell
docker run -it -v ${PWD}:/src ghcr.io/b1nary-gr0up/nwa:main add \
  --copyright "Clément Latzarus" \
  --license agpl-3.0-only \
  assets/**/*.css \
  public/common/css/*.css
```