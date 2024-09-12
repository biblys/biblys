# Install

This document describes how to install Biblys on a local development environment.
It is intended for developers who want to contribute to the project or test it locally,
but not recommended for production use.

## Requirements

- Docker to use provided images:
  - Apache web server with PHP 8.1+
  - MySQL 8.0 / MariaDB
  - [Mailpit](https://mailpit.axllent.org/)
- [Composer](https://getcomposer.org/)

## Local install for development

### 1. Clone git repository

```shell
git clone git@github.com:biblys/biblys.git
```

### 2. Install composer dependencies

```shell
cd biblys
composer install
```

### 3. Install starter theme

```shell
git clone git@github.com:biblys/biblys-theme-starter.git app
composer run theme:refresh
```

### 4. Copy the configuration file

```shell
cp config.example.yml app/config.yml
```

### 5. Add database credentials to config file

e.g. when using provided MySQL docker image:

```shell
db:
  host: biblys-mysql
  host_for_cli: 127.0.0.1
  port: 3306
  user: root
  pass:
  base: biblys
```

### 6. Add SMTP credentials to config file

Ability to send email is required for login.

eg. when using provided Mailpit docker image:

```shell
smtp:
  host: biblys-mailpit
  user: sender@example.com
  pass: password123
  port: 1025
```

### 7. Start docker containers

```shell
docker compose up -d
```

### 8. Create database

```shell
composer run propel:migrate
```

### 9. Add authentication secret

Generate a 32-chars long random string, e.g. with openssl:

```shell
openssl rand -hex 16
```

and add it to `app/config.yml` file:

```yaml
authentication:
  secret: abcd1234…
```

### 10. Add seeds

```shell
composer run db:seed
```

### 11. Access site and login

Go to http://localhost:8088/

And login with admin account `admin@paronymie.fr`
