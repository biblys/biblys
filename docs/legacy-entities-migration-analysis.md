# Analyse de migration : classes legacy dans `inc/`

**Date :** 2026-06-22  
**Statut :** En cours

---

## Contexte

Le répertoire `inc/` contient des classes Entity legacy héritant d'une classe de base `Entity` qui implémente `ArrayAccess`. Ces classes coexistent avec des modèles Propel générés depuis `schema.xml` qui couvrent les mêmes tables.

Toutes les 44 classes Entity ont un modèle Propel correspondant sur la même table — aucune migration de schéma n'est nécessaire.

---

## Inventaire

### Correspondance legacy → Propel

Toutes les 44 classes Entity ont un modèle Propel sur la même table — aucune migration de schéma n'est nécessaire. Les noms de classes diffèrent dans 7 cas :

| Classe legacy | Modèle Propel |
|---|---|
| `CFCampaign` | `CrowdfundingCampaign` |
| `CFReward` | `CrowfundingReward` |
| `Category` | `BlogCategory` |
| `Collection` | `BookCollection` |
| `Liste` | `StockItemList` |
| `Rayon` | `ArticleCategory` |
| `Shipping` | `ShippingOption` |

### Classes utilitaires (pas de table)

- **`Media`** — logique de gestion de fichiers ; partiellement remplacée par `ImagesService`
- **`Visitor`** — wrapper de session utilisateur ; utilise `UserQuery` en interne

---

## Avancement

### Supprimées (2026-06-22)

| Classe | Méthode | Notes |
|---|---|---|
| `Price` | Suppression directe | Aucun usage, modèle Propel déjà utilisé |
| `Redirection` | Suppression directe | Aucun usage, modèle Propel déjà utilisé |
| `Signing` | Suppression directe | Aucun usage, modèle Propel déjà utilisé |
| `Subscription` | Suppression directe | Aucun usage, modèle Propel déjà utilisé |
| `Download` | Migration de `File::addDownloadBy()` | Remplacé par `Model\Download` + `->save()` |
| `Alert` | Migration de `Order::deleteRelatedAlerts()` et `log_myalerts.php` | Remplacé par `AlertQuery` |

### Bloquées par dépendances croisées internes à `inc/`

Ces classes n'ont aucun usage direct dans `src/` mais sont encore appelées par d'autres classes legacy :

| Classe | Appelée par |
|---|---|
| `Media` | `Article.class.php`, `Post.class.php`, `People.class.php` |
| `Option` | `Site.class.php` |
| `Right` | `Publisher.class.php`, `Visitor.class.php` |
| `Session` | `Visitor.class.php` |
| `Wish` | `Cart.class.php` |
| `Wishlist` | `Cart.class.php` |

Elles pourront être supprimées une fois leurs classes appelantes migrées.

---

## Chiffres clés

| Métrique | Valeur |
|---|---|
| Classes legacy dans `inc/` au départ | 46 (44 Entity + 2 utilitaires) |
| Classes supprimées | 6 |
| Classes restantes | 40 |
| Lignes de code legacy restantes | ~7 600 |
| Instanciations de `*Manager` dans `src/` | 129 |
| Instanciations de `*Manager` dans `tests/` | 244 |
| Fichiers `src/` touchés | 28 |
| Fichiers de tests touchés | 27+ |

### Top 5 classes les plus utilisées

| Classe | src/ | tests/ | Total |
|---|---|---|---|
| `ArticleManager` | 63 | 36 | **99** |
| `CartManager` | 35 | 27 | **62** |
| `StockManager` | 34 | 25 | **59** |
| `OrderManager` | 32 | 26 | **58** |
| `PublisherManager` | 19 | 12 | **31** |

---

## Principaux obstacles

1. **`Entity` est la superclasse de tout** — elle ne peut être supprimée qu'en dernier, une fois toutes les sous-classes migrées.

2. **Accès ArrayAccess** — le pattern `$entity['field']` est utilisé dans les contrôleurs, services et templates Twig. Chaque accès doit être converti en `$entity->getField()`.

3. **`Article` seul est un chantier majeur** — 99 instanciations, 1758 lignes, présent dans 13 fichiers `src/`.

4. **Le code récent utilise encore les legacy** — des usecases récents (ex: `CancelOrderUsecase`) appellent encore `OrderManager` et `StockManager`.

5. **`EntityFactory` dans les tests** — wrapper legacy utilisé dans 21 fichiers de tests, à migrer en parallèle des classes de production.

6. **Dépendances croisées dans `inc/`** — les classes legacy s'appellent mutuellement (ex: `Article` appelle `StockManager`, `PublisherManager`), rendant les migrations isolées délicates.

---

## Estimation de la difficulté

**Globale : Très difficile** (projet de plusieurs mois)

### Stratégie de migration

**Étape 1 — ✅ Suppression directe** *(fait)*  
`Price`, `Redirection`, `Signing`, `Subscription`

**Étape 2 — Migration des dépendances croisées** *(en cours)*  
Migrer les appels legacy dans les classes encore dépendantes pour débloquer leur suppression :

| Classe appelante | Appel migré | Classe débloquée | Statut |
|---|---|---|---|
| `File.class.php` | `DownloadManager` → `Model\Download` | `Download` | ✅ fait |
| `Order.class.php` + `log_myalerts.php` | `AlertManager` → `AlertQuery` | `Alert` | ✅ fait |
| `Cart.class.php` | `WishManager`, `WishlistManager` | `Wish`, `Wishlist` | ⏳ à faire |
| `Site.class.php` | `OptionManager` | `Option` | ⏳ à faire |
| `Publisher.class.php` | `RightManager` | `Right` (partiel) | ⏳ à faire |
| `Visitor.class.php` | `RightManager`, `SessionManager` | `Right`, `Session` | ⏳ à faire |
| `Article/Post/People.class.php` | `Media` | `Media` | ⏳ à faire |

Puis : `People`, `Post`, `Tag`, `Supplier`, `Inventory`, `Customer`, `Lang`, `Link`, `Role`

**Étape 3 — Migration des classes majeures**  
À traiter une par une, en commençant par les contrôleurs puis les templates :  
`Order` → `Stock` → `Cart` → `Article`

**Étape 4 — Classes utilitaires**  
`Visitor` (wrapper de session) → `Media` (logique fichiers, déjà partiellement remplacée par `ImagesService`)

**Étape 5 — Suppression de `Entity` et `EntityManager`**  
Une fois toutes les sous-classes migrées.
