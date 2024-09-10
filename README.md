# Biblys

Biblys is an open-source web application designed to build online bookshops,
primarily used by French independent book publishers.

## Project Status

The open-source version is an ongoing project, and I currently do not recommend
using it in production due to potential significant changes. The goal is to
release a stable, well-documented version 3.0 by the end of 2024.

It's important to note that Biblys' user interface is only available in French
at the moment. Making it translatable will require substantial work. If you're
interested in using Biblys in another language, please open an issue.

Feel free to start a discussion if you need more information about the project
status or any other topic.

## Requirements

- PHP 8.1 or above
- MySQL 8
- Composer
- Git
- Yarn

## Install

See [./INSTALL.MD]()

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

## Deploy script (biblys.cloud)

```shell
./script/deploy {site} {version}
```
