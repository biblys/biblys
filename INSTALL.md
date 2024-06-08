# Install

## Requirements

- Docker to use provided images:
  - Apache web server with PHP 8.1+
  - MySQL 8.0 / MariaDB
- [Composer](https://getcomposer.org/)
- SMTP service (or [Mailpit](https://mailpit.axllent.org/))

## Local install for development

1. Clone git repository

```shell
git clone git@github.com:clemlatz/biblys.git --depth=100
```

2. Install composer dependencies

```shell
cd biblys
composer install
```

3. Install starter theme

```shell
git clone git@github.com:biblys/biblys-theme-starter.git app
composer run theme:refresh
```

4. Copy configuration file

```shell
cp config.example.yml app/config.yml
```

5. Add database credentials to config file

eg. when using provided MySQL docker image: 

```shell
db: 
  host: host.docker.internal
  host_for_cli: 127.0.0.1
  port: 3306
  user: root
  pass:
  base: biblys
```

6. Add SMTP credentials to config file

Ability to send email is required for login.

eg. when using Mailpit:

```shell
smtp:
  host: host.docker.internal
  user: sender@example.com
  pass: password123
  port: 1025
```

5. Start docker containers

```shell
docker-compose up -d
```

6. Create database

```shell
composer run propel:migrate
```

7. Add authentication secret

Generate a 32-chars long random string, eg. with openssl:

```shell
openssl rand -hex 16
```

and add it to `app/config.yml` file:

```yaml
authentication:
  secret: abcd1234…
```

8. Add seeds

```shell
composer run db:seed
```

9. Access site and login

Go to http://localhost:8088/

And login with admin account `admin@paronymie.fr`