# Contribuer à Biblys

Ce document décrit le workflow git et les conventions utilisées dans ce dépôt.

## Modèle de branches

Ce dépôt suit le modèle de branches [git flow](https://nvie.com/posts/a-successful-git-branching-model/).

### Branches principales

- `main` - branche de production. Reflète toujours la dernière version stable. Taguée avec les numéros de version stables (ex. `3.13.0`).
- `dev` - branche de développement. Branche d'intégration pour le travail en cours. Taguée avec des versions de pré-release `-dev` (ex. `3.14.0-dev`, `3.13.0-dev.1`).

### Branches secondaires

Créées depuis `dev`, fusionnées dans `dev` via une Pull Request :

- `feature/*` - nouvelles fonctionnalités
- `fix/*` - corrections de bugs
- `chore/*` - tâches de maintenance (dépendances, outillage, nettoyage)
- `refactor/*` - refactoring du code sans changement de comportement
- `docs/*` - changements de documentation

Créées depuis `dev`, fusionnées dans `main` et `dev` :

- `release/*` - préparation de release (bump de version, finalisation du CHANGELOG)

Créées depuis `main`, fusionnées dans `main` et `dev` :

- `hotfix/*` - corrections urgentes pour des problèmes en production

## Versioning

Les versions suivent le [Semantic Versioning](https://semver.org/) (`MAJOR.MINOR.PATCH`).

- Les versions sur `dev` portent un suffixe `-dev` (ex. `3.14.0-dev`). Si une branche de release nécessite plusieurs itérations avant d'être fusionnée, incrémenter le suffixe (ex. `3.13.0-dev.1`, `3.13.0-dev.2`).
- Les versions sur `main` sont stables, sans suffixe (ex. `3.13.0`).
- Les versions de hotfix incrémentent le numéro `PATCH` de la dernière release sur `main` (ex. `3.12.1` -> `3.12.2`).

## Workflows types

### Démarrer une feature

1. Créer une branche depuis `dev` : `git checkout -b feature/my-feature dev`
2. Commiter les changements en suivant la convention [Conventional Commits](#messages-de-commit).
3. Ouvrir une Pull Request vers `dev`.

### Préparer une release

1. Créer une branche depuis `dev` : `git checkout -b release/X.Y.Z dev`
2. Mettre à jour `CHANGELOG.md` : remplacer le titre `## X.Y.Z (DEV)` par `## X.Y.Z (date)`.
3. Mettre à jour le numéro de version si nécessaire.
4. Ouvrir une Pull Request vers `main`. Pour une release mineure, le titre suit le format `Mise à jour de <mois> <année> (X.Y.Z)`, où `<mois> <année>` est le mois courant en français suivi de l'année (ex. : `Mise à jour de juillet 2026 (3.14.0)`).
5. Fusionner la Pull Request par **rebase** (« Rebase and merge ») : le dépôt n'autorise que cette méthode, il n'y a donc pas de commit de fusion. Taguer sur `main` le commit de version `X.Y.Z` obtenu après le rebase.
6. Fusionner `main` dans `dev` et ajouter une nouvelle section `## <prochaine-version> (DEV)` en haut de `CHANGELOG.md`.

### Corriger la production (hotfix)

1. Créer une branche depuis `main` : `git checkout -b hotfix/X.Y.Z main`
2. Corriger le problème et ajouter une section `## X.Y.Z (date)` dans `CHANGELOG.md`.
3. Ouvrir une Pull Request vers `main`. Pour une release patch, le titre suit le format `Correctif X.Y.Z`, où `X.Y.Z` est le numéro de version (ex. : `Correctif 3.14.1`).
4. Fusionner la Pull Request par **rebase** (« Rebase and merge ») : le dépôt n'autorise que cette méthode, il n'y a donc pas de commit de fusion. Taguer sur `main` le commit de version `X.Y.Z` obtenu après le rebase.
5. Fusionner `main` dans `dev`.

## CHANGELOG.md

Pendant le travail sur `dev`, les changements en cours sont consignés sous un titre `## X.Y.Z (DEV)` en haut de `CHANGELOG.md`. Les entrées sont rédigées en français et regroupées sous des titres de catégories (ex. `### Améliorations`, `### Corrections`, `### Conformité NF525`).

Lors d'une release, le `(DEV)` est remplacé par la date de la release.

## Messages de commit

Les messages de commit suivent le format [Conventional Commits](https://www.conventionalcommits.org/) :

```
type(scope): description
```

Types courants utilisés dans ce projet : `feat`, `fix`, `chore`, `docs`, `refactor`, `tests`, `build`.

## Langue

- Les **issues et Pull Requests** sont rédigées en **français**. Les titres de PR sont en français et sans préfixe de type Conventional Commits (ex. : "Ajouter le job build-assets à la CI", pas "ci: add build-assets job").
- Les **messages de commit et les noms de branches** sont rédigés en **anglais**.
