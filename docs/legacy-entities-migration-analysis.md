# Analyse de migration : classes legacy dans `inc/`

**Date :** 2026-06-22  
**Statut :** Analyse — pas encore de décision de migration

---

## Contexte

Le répertoire `inc/` contient 44 classes Entity legacy (+ 2 utilitaires) héritant d'une classe de base `Entity` qui implémente `ArrayAccess`. Ces classes coexistent avec des modèles Propel générés depuis `schema.xml` qui couvrent les mêmes tables.

---

## Inventaire

### Correspondance legacy → Propel

Toutes les 44 classes Entity legacy ont un modèle Propel correspondant sur la même table. Aucune migration de schéma n'est nécessaire.

Cas où les noms de classes diffèrent :

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

- **`Media`** — logique de gestion de fichiers, remplacée par `ImagesService`
- **`Visitor`** — wrapper de session utilisateur, utilise `UserQuery` en interne

---

## Chiffres clés

| Métrique | Valeur |
|---|---|
| Classes legacy dans `inc/` | 46 (44 Entity + 2 utilitaires) |
| Lignes de code legacy | ~8 000 |
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

### Stratégie recommandée

**Étape 1 — Suppression rapide (risque quasi nul)**  
Classes sans usage actif dans `src/`, modèle Propel existant, ≤ 67 lignes :  
`Alert`, `Customer`, `Download`, `Lang`, `Link`, `Option`, `Price`, `Redirection`, `Right`, `Role`, `Session`, `Signing`, `Subscription`, `Wish`, `Wishlist`

**Étape 2 — Migration des classes moyennes**  
Classes avec peu d'usages dans `src/` : `People`, `Publisher`, `Post`, `File`, `Site`, `Tag`, `Supplier`, `Inventory`, `Signing`

**Étape 3 — Migration des classes majeures**  
À traiter une par une, en commençant par les contrôleurs puis les templates :  
`Order` → `Stock` → `Cart` → `Article`

**Étape 4 — Suppression de `Entity` et `EntityManager`**  
Une fois toutes les sous-classes migrées.
