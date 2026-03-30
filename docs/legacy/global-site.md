# La variable globale `LEGACY_CURRENT_SITE`

## Contexte historique

Biblys supportait historiquement plusieurs sites dans la même base de données.
Chaque entité "site-dépendante" était associée à un `site_id`. Le site courant
était stocké dans `$GLOBALS["LEGACY_CURRENT_SITE"]` et représenté par la classe
legacy `Site` (dans `inc/`).

Ce mécanisme a été remplacé dans le code moderne par le service `CurrentSite` (
dans `src/Biblys/Service/`), injecté via le container Symfony. La variable
globale subsiste pour la compatibilité avec le code legacy.

## Fonctionnement de `LegacyCodeHelper::getGlobalSite()`

```php
// src/Biblys/Legacy/LegacyCodeHelper.php

public static function getGlobalSite($ignoreDeprecation = false): Site
{
    if (!isset($GLOBALS["LEGACY_CURRENT_SITE"])) {
        $config = Config::load();
        $currentSiteId = $config->get("site");
        $currentSite = SiteQuery::create()->findPk($currentSiteId);
        if (!$currentSite) {
            throw new Exception("Unable to find site with id $currentSiteId");
        }
        self::setGlobalSite($currentSite);
    }

    return $GLOBALS["LEGACY_CURRENT_SITE"];
}

public static function setGlobalSite(\Model\Site $site): void
{
    $GLOBALS["LEGACY_CURRENT_SITE"] = new Site([
        "site_id" => $site->getId(),
        "site_title" => $site->getTitle(),
        "site_domain" => $site->getDomain(),
        "site_contact" => $site->getContact(),
        "site_tag" => $site->getTag(),
    ]);
}
```

`getGlobalSite()` retourne un objet `Site` **legacy** (classe
`inc/Site.class.php`), pas un `\Model\Site` Propel.

Si `$GLOBALS["LEGACY_CURRENT_SITE"]` n'est pas défini, elle tente de charger le
site depuis la config (`site: <id>`). Si ce site n'existe pas en base, elle lève
une exception.

## Impact sur les tests legacy

### Problème : dépendance au site fixture partagé

Les 14 fichiers de tests legacy dans `tests/` (racine) appellent
`LegacyCodeHelper::getGlobalSite()` pour initialiser le site courant. Ils
dépendent du site créé par `createFixtures()` dans `tests/setUp.php`, qui
bootstrap la suite de tests PHPUnit.

`SiteConfigureCommandTest::setUp()` appelle `SiteQuery::create()->deleteAll()`,
supprimant **tous** les sites en base. Si ce test s'exécute avant les tests
legacy, `getGlobalSite()` échoue avec :

```
Exception: Unable to find site with id 1
```

### Solution : chaque test crée son propre site

Remplacer `LegacyCodeHelper::getGlobalSite()` par `ModelFactory::createSite()` +
`LegacyCodeHelper::setGlobalSite()` dans chaque test. Le site est créé en base
ET défini comme site global, rendant chaque test indépendant.

**Quand `$globalSite` n'est pas utilisé directement :**

```php
LegacyCodeHelper::setGlobalSite(ModelFactory::createSite());
```

**Quand `$globalSite` est utilisé (ex: `->get('id')`, `->setOpt()`):**

```php
$site = ModelFactory::createSite();
LegacyCodeHelper::setGlobalSite($site);
$globalSite = LegacyCodeHelper::getGlobalSite(ignoreDeprecation: true);
```

`ignoreDeprecation: true` est passé pour éviter le warning de dépréciation dans
les tests (la méthode est dépréciée car elle doit être remplacée par
`CurrentSite`).

### Cas particuliers

**`setUpBeforeClass()` :** Si le `setUp` statique appelle du code qui utilise
`getGlobalSite()` (ex: `CollectionTest` crée un publisher avant les tests), le
site doit être initialisé dans `setUpBeforeClass()` :

```php
public static function setUpBeforeClass(): void
{
    LegacyCodeHelper::setGlobalSite(ModelFactory::createSite());
    // ... reste du setUp
}
```

**`@backupGlobals disabled` :** Les 14 fichiers legacy déclarent
`@backupGlobals disabled`. Cela signifie que `$GLOBALS["LEGACY_CURRENT_SITE"]` *
*persiste entre les méthodes de test** d'une même classe. Un site initialisé
dans `setUpBeforeClass()` est donc accessible dans toutes les méthodes de test.

### Code legacy qui utilise `getGlobalSite()` en interne

Certaines classes legacy (`Rayon::addArticle()`, `Entity::create()` pour les
entités non-site-agnostiques, etc.) appellent `getGlobalSite()` en interne.
C'est pourquoi il ne suffit pas de supprimer l'appel dans le test — il faut
impérativement que `$GLOBALS["LEGACY_CURRENT_SITE"]` soit défini ET que le site
existe en base.

Exemples :

- `Entity::create()` : si `$this->siteAgnostic === false`, ajoute `site_id`
  depuis le site global
- `Entity::getAll()` : si `$this->siteAgnostic === false`, filtre par `site_id`
  du site global
- `Rayon::addArticle()` : utilise `getGlobalSite()->get('id')` pour créer les
  liens `rayon ↔ article`
- `ArticleManager::addSiteFilters()` : applique des filtres éditeur/collection
  depuis les opts du site global

## Cas non résolu : `RayonTest::testAddArticleAlreadyInRayon`

Ce test échoue même en isolation après correction du problème de site fixture.
L'erreur attendue est :

```
L'article « Banane » est déjà dans le rayon « Fruits & légumes ».
```

L'erreur réelle est :

```
Il existe déjà un article avec l'url banane
```

### Investigation

La chaîne d'appel impliquée :

1. `$rayon->addArticle($article)` (2ème appel)
2. → `$lm->get(...)` ne trouve pas le lien (créé lors du 1er appel)
3. → Tente de créer un nouveau lien
4. → `$am->update($article)` → `validate()` → détecte url "banane" en doublon

Pistes explorées :

- **Cache EntityManager** (`$this->entities[$id]`) : non pertinent car
  `new LinkManager()` est créé à chaque appel.
- **Site filter dans `Entity::getAll()`** : non pertinent car les articles sont
  `siteAgnostic = true`.
- **`addSiteFilters()`** : n'ajoute que des filtres éditeur/collection, pas de
  `site_id`.
- **`@backupGlobals disabled`** : le site global persiste correctement entre les
  méthodes.
- **Conflits d'URL "banane"** : seul article avec cet URL, vérifié après
  `db:prepare`.
- **`EntityFactory::createContribution`** appelle
  `ModelFactory::createContribution(Model\Article, Model\People)` avec des
  objets legacy — potentiel `TypeError` PHP 8 non résolu.

### Conclusion

Ce bug est **pré-existant** : `testCreate` échoue toujours en premier (exception
site) sur la branche `dev`, rendant `testAddArticleAlreadyInRayon` inaccessible.
La correction du site fixture expose ce bug latent qui nécessite une
investigation séparée.

## Références

- `LegacyCodeHelper` : `src/Biblys/Legacy/LegacyCodeHelper.php`
- `ModelFactory::createSite()` : `src/Biblys/Test/ModelFactory.php`
- `Entity::getAll()` avec `siteAgnostic` : `inc/Entity.class.php:403`
- `ArticleManager::addSiteFilters()` : `inc/Article.class.php:1178`
- `Rayon::addArticle()` : `inc/Rayon.class.php:48`
- Issue GitHub : biblys/biblys#397
