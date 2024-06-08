# Biblys

## Requirements

- PHP 8.1 or above
- MySQL 8
- Composer
- Git
- Yarn

## Install

See [INSTALL.MD]()

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
