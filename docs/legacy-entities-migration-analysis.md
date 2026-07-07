# Analyse de migration : classes legacy dans `inc/`

**Date :** 2026-06-22  
**Statut :** En cours

---

## Contexte

Le répertoire `inc/` contient des classes Entity legacy héritant d'une classe de base `Entity` qui implémente `ArrayAccess`. Ces classes coexistent avec des modèles Propel générés depuis `schema.xml` qui couvrent les mêmes tables.

Toutes les 44 classes Entity ont un modèle Propel correspondant sur la même table — aucune migration de schéma n'est nécessaire.

---

## Processus par étape

Pour chaque classe legacy à migrer :

1. **Écrire des tests de non-régression** sur les méthodes concernées (si elles ne sont pas déjà couvertes)
2. **Implémenter les modifications** : remplacer les appels `*Manager` par des requêtes Propel (`*Query`) ou des modèles Propel directs
3. **Supprimer les fichiers devenus inutiles** (`inc/*.class.php`)
4. **Vérifier que les tests passent** et corriger si besoin
5. **Mettre à jour ce document** (tableau d'avancement, chiffres clés)

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
| `Wish` | Migration de `Cart::removeStock()` et `log_mywishes.php` | Remplacé par `WishQuery` |
| `Wishlist` | Migration de `log_mywishes.php` et `log_wishlist.php` | Remplacé par `WishlistQuery` |
| `Option` | Migration de `Site::getOpt()` et `Site::setOpt()` | Remplacé par `OptionQuery` (corrige aussi un bug de mise à jour) |
| `Right` | Migration de `Publisher::getRights()` et `Visitor::getCurrentRight()` + 4 contrôleurs legacy | Remplacé par `RightQuery` |
| `Session` | Migration de `Visitor::_setUserFromToken()` | Remplacé par `SessionQuery` |
| `Tag` | Migration de `TagController` + `Article::countAllFromTag/getAllFromTag` | Remplacé par `TagQuery`, hook `preSave` pour le slug |
| `Inventory` | Migration de `InventoryController` (4 méthodes) | Remplacé par `InventoryQuery` |

### Bloquées par l'API de thèmes

Ces classes sont utilisées dans des méthodes `@deprecated` de classes legacy qui font partie de l'**API publique consommée par les thèmes Biblys**. Le périmètre à auditer est double :
- les thèmes versionnés dans `../sites/` (dépôt séparé)
- le thème actuellement chargé dans `app/` (copie de travail locale, non versionnée ici)

Leur suppression ne peut intervenir qu'après audit complet de ces deux emplacements.

| Classe | Méthodes concernées | Statut |
|---|---|---|
| `Media` | `Article::getCover()`, `Post::getIllustration()`, `People::getPhoto()` et variantes | ⏸️ différé — compatibilité thèmes |

---

## Chiffres clés

| Métrique | Valeur |
|---|---|
| Classes legacy dans `inc/` au départ | 46 (44 Entity + 2 utilitaires) |
| Classes supprimées | 13 |
| Classes restantes | 33 |
| Lignes de code legacy restantes | ~7 300 |
| Instanciations de `*Manager` dans `src/` | 129 |
| Instanciations de `*Manager` dans `controllers/` | 109 |
| Instanciations de `*Manager` dans `tests/` | 244 |
| Fichiers `src/` touchés | 28 |
| Fichiers `controllers/` touchés | 55 |
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
| `Cart.class.php` + `log_mywishes.php` + `log_wishlist.php` | `WishManager`, `WishlistManager` | `Wish`, `Wishlist` | ✅ fait |
| `Site.class.php` | `OptionManager` → `OptionQuery` | `Option` | ✅ fait |
| `Publisher.class.php` | `RightManager` → `RightQuery` | `Right` | ✅ fait |
| `Visitor.class.php` | `RightManager` → `RightQuery` + 4 contrôleurs | `Right` | ✅ fait |
| `Visitor.class.php` | `SessionManager` → `SessionQuery` | `Session` | ✅ fait |
| `TagController.php` | `TagManager` → `TagQuery` | `Tag` | ✅ fait |
| `InventoryController.php` | `InventoryManager` → `InventoryQuery` | `Inventory` | ✅ fait |
| `Article/Post/People.class.php` | `Media` | `Media` | ⏳ à faire |

Puis : `People`, `Post`, `Tag`, `Supplier`, `Inventory`, `Customer`, `Lang`, `Link`, `Role`

**Étape 3 — Migration des classes majeures**  
À traiter une par une, en commençant par les contrôleurs puis les templates :  
`Order` → `Stock` → `Cart` → `Article`

**Étape 4 — Classes utilitaires**  
`Visitor` (wrapper de session) → `Media` (logique fichiers, déjà partiellement remplacée par `ImagesService`)

**Étape 5 — Suppression de `Entity` et `EntityManager`**  
Une fois toutes les sous-classes migrées.
