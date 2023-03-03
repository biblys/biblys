# Biblys

## Requirements

- PHP 8.0 or above
- MySQL 5.7
- Composer
- Git
- Yarn

## Install

```console
git clone git@github.com:/biblys/biblys.git
cd biblys
composer install
mkdir app
cp config.example.yml app/config.yml
vim app/config.yml
```

In dev mode:

```console
yarn
composer build
```

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
