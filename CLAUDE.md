# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Biblys is a PHP web application for building online bookshops, primarily used by French independent book publishers. It uses Symfony components (HttpKernel, Routing, Form, etc.) but is not a full Symfony application.

## Development Commands

```bash
# Run all tests (resets DB, runs migrations, then PHPUnit)
composer test

# Run a single test file or directory
composer test:path tests/ArticleTest.php

# Run JavaScript tests
composer test:js

# Build assets (dev mode with watch)
composer build:watch

# Build assets (production)
composer build:prod

# Theme management
composer theme:refresh    # Copy theme files to public
composer theme:download   # Pull latest theme from Git
composer theme:update     # Download + refresh
composer theme:watch      # Auto-refresh on file changes

# Database operations
composer db:reset         # Reset database
composer db:migrate       # Run Propel migrations
composer db:seed          # Seed database
composer db:prepare       # Reset + migrate + seed

# Propel ORM commands
composer propel:build     # Generate model classes from schema.xml
composer propel:diff      # Generate migration from schema changes
composer propel:migrate   # Apply migrations

# Console commands
bin/console               # Symfony Console application
```

## Architecture

### Entry Points
- `public/index.php` - Main web entry point, uses Symfony HttpKernel
- `public/api.php` - API entry point
- `bin/console` - CLI commands

### Directory Structure
- `src/AppBundle/Controller/` - Web controllers with routes defined in `src/AppBundle/routes.yml`
- `src/ApiBundle/Controller/` - API controllers with routes in `src/ApiBundle/routes.yml`
- `src/Model/` - Propel ORM models generated from `schema.xml`
- `src/Model/Base/` - Auto-generated Propel base classes (do not edit)
- `src/Biblys/Service/` - Business services (Config, CurrentUser, Mailer, PaymentService, etc.)
- `src/Framework/` - Framework components (Controller base class, RouteLoader, ArgumentResolvers)
- `src/Usecase/` - Business use cases (AddArticleToUserLibraryUsecase, etc.)
- `inc/` - Legacy entity classes (Article.class.php, Order.class.php, etc.) implementing ArrayAccess
- `tests/` - PHPUnit tests mirroring src structure

### Dependency Injection
`src/container.php` configures Symfony's ContainerBuilder with custom ArgumentValueResolvers for injecting services into controller actions (CurrentUser, Config, TemplateService, etc.).

### ORM
Uses Propel 2.x ORM. Database schema is defined in `schema.xml`. Run `composer propel:build` after schema changes to regenerate model classes.

Prefer using repository classes (e.g. `PaymentRepository`) for reading and writing data rather than calling Propel model relations directly (e.g. `$order->getPayments()`). Repositories live in `src/Repository/` and ensure fresh DB queries, avoiding Propel's in-memory collection cache.

### Legacy Code
The `inc/` directory contains legacy entity classes that extend a base `Entity` class implementing ArrayAccess. These are being gradually replaced by Propel models in `src/Model/`.

### Assets
Frontend assets are built with Webpack Encore. Source lives in `assets/` (e.g. `assets/js/`). There are two build outputs with **different Git tracking**:

- `composer build:prod` writes to `public/assets/bundle/` — **this directory is committed to Git** (content-hashed filenames + `manifest.json`/`entrypoints.json`). After changing a source asset, run `composer build:prod` and commit the regenerated bundle, otherwise deployed pages serve the old code.
- `composer build` / `composer build:watch` (dev mode) writes to `public/build/` — **gitignored**, so dev builds never produce anything to commit.

Do not run formatters on the generated bundle files; they are minified build artifacts, not source.

### Configuration
Copy `config.example.yml` to `app/config.yml`. Configuration includes database, SMTP, payment providers (Stripe, PayPlug, PayPal), mailing services (Mailjet, Brevo), and various integrations.

### Testing
Tests use PHPUnit 10. Test setup in `tests/setUp.php` initializes Propel and creates fixtures. Tests run with `PHP_ENV=test` which uses a separate test database.

## Git Workflow

See [CONTRIBUTING.md](./CONTRIBUTING.md) for the git flow branching model, versioning, and commit conventions used in this repository.

### Langue — règle critique

- **Titres de PR et issues** : en français, **sans** préfixe Conventional Commits (`feat:`, `fix:`, `chore:`…)
- **Messages de commit et noms de branches** : en anglais, avec préfixe Conventional Commits

| ❌ FAUX (titre de PR) | ✅ CORRECT (titre de PR) |
|---|---|
| `feat: add point-of-sale scaffolding` | `Caisse : scaffolding et template Twig` |
| `fix: correct HTML entity escaping` | `Corriger l'échappement des entités HTML` |

## Docker Development

```bash
docker-compose up -d           # Start containers
docker-compose up --build      # Rebuild and start
```
