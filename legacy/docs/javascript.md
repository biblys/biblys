# JavaScript legacy

## Contexte historique

Le JavaScript de Biblys vit dans deux systèmes qui coexistent :

| | `public/common/js/` (hérité) | `assets/js/` (moderne) |
|---|---|---|
| Servi | Tel quel, par `<script>` ou `import` d'une URL fixe | Compilé par Webpack Encore, noms hashés |
| Style | Scripts globaux, jQuery, `window.*` | Modules ES, classes, `import` |
| Tests | Difficiles (globaux, pas de module) | vitest, avec happy-dom pour le DOM |
| Chargé | Après jQuery, par `_assets_js.html.twig` | Avant jQuery, via `get_assets("js")` |

Le code hérité est migré petit à petit vers `assets/js/`, fonctionnalité par
fonctionnalité. Tout nouveau code JavaScript va dans `assets/js/`.

## État actuel

Fichiers de `public/common/js/` (nombre de lignes et d'appels `$(`, approximatifs) :

| Fichier | Lignes | `$(` | Chargé pour | Contenu |
|---|---|---|---|---|
| `common.js` | 1115 | 110 | Tout le monde | Globaux : `notify`, `_alert`, `currency`, `price`, `safeHtml`, `Biblys.*`, handlers jQuery (`[data-confirm]`, autocomplétion…) |
| `article_edit.js` | 1071 | 123 | Éditeurs et admins (module) | Page d'édition d'article, 4 `EntitySearchField` |
| `biblys-admin.js` | 705 | 54 | Admins | Upload d'images et de fichiers, barre d'admin, abonnements aux notifications, raccourci Ctrl+Shift+A |
| `entity-search-field.js` | 373 | 0 | Importé par URL | Champ de recherche avec complétion (module ES) |
| `adm_reorder.js` | 243 | 17 | Admins | Page de réassort : chargement des collections et de leurs articles |
| `adm_stocks.js` | 149 | 0 | Admins | Modification de champs d'exemplaires (`/x/adm_stock`) |
| `_list.js` | 81 | 0 | Tout le monde | Filtres des listes (bouton « Actualiser ») |
| `adm_publisher_stock.js` | 63 | 0 | Admins | Mise à jour du stock d'un article par l'éditeur |

Hors de ce dossier et hors Webpack :

- `public/assets/js/payment-methods.js` et `analytics.js`, servis tels quels
- `public/theme/main.js`, copié depuis le thème (`composer theme:refresh`)
- `public/libs/` : bibliothèques embarquées (CKEditor, tooltipster, sorttable…)
- jQuery, jQuery UI et Bootstrap 4, chargés depuis des CDN
- des scripts inline dans 8 templates Twig et 3 controllers legacy

Déjà migré :

- la caisse (`assets/js/point-of-sale/`, issue #615)
- les modules de `assets/js/` : commandes, expédition, termes de recherche,
  raccourcis d'admin, éditeur de templates, export CSV, tâches cron

`EntitySearchField` reste dans `public/common/js/` car il est importé par son
URL `/common/js/entity-search-field.js` depuis `article_edit.js`, les templates
`Shipping/zone_countries` et `StockItem/new`, le script `autoload` de
`Utils/_entity-search-field.html.twig` (utilisé par `Post/articles`,
`Rayon/addArticle` et `SpecialOffer/_form`) et le module de la caisse.

## Contraintes pour le code moderne

### Ordre de chargement

Les bundles Encore sont chargés **avant** jQuery et `common.js`. Un module ne
peut pas utiliser `$`, `notify`, `_alert`, `currency` ou `safeHtml` au moment
de son chargement. Deux solutions :

- réimporter l'équivalent natif depuis `assets/js/` : `helpers/formatAmount`,
  `helpers/eurosToCents`, `helpers/safeHtml`, `notification` ;
- appeler le global au moment de l'exécution, pas à l'import
  (`window._alert(message)` dans une méthode, par exemple).

Ces helpers existent en double (dans `common.js` et dans `assets/js/helpers/`)
tant que `common.js` est chargé.

### Importer un fichier hérité depuis un module

Webpack essaie de résoudre un `import()` vers `public/` et échoue. Il faut
lui demander de le laisser tel quel, et prévenir vitest de la même façon.
L'URL doit être dans une constante, car `@vite-ignore` ne s'applique pas à
un chemin littéral :

```js
const ENTITY_SEARCH_FIELD_URL = '/common/js/entity-search-field.js';

await import(/* webpackIgnore: true */ /* @vite-ignore */ ENTITY_SEARCH_FIELD_URL);
```

Voir `assets/js/point-of-sale/point-of-sale.js`.

### Handlers jQuery globaux

`common.js` attache des handlers sur des attributs génériques. Le plus piégeux
est `[data-confirm]` : il affiche un `confirm()` à chaque clic. Un module qui
pose sa propre confirmation sur le même élément en affichera donc deux. Utiliser
un autre nom d'attribut (par exemple `data-clear_cart_confirmation`).

### Tests

Le code hérité est difficile à importer dans un test. Pour tester un module :

- environnement DOM par fichier avec `// @vitest-environment happy-dom` ;
- dépendances (alerte, notification, `confirm`, redirection) injectables avec
  une valeur par défaut, comme dans `PointOfSale`.

## Migrer une fonctionnalité

1. Créer un module dans `assets/js/` (une classe `export default` dont le
   constructeur reçoit l'élément racine) et l'enregistrer dans `assets/js/admin.js`
   ou `app.js` avec `loadModuleForElements('mon-module', '#mon-element')`.
2. Ajouter un élément racine dans le template Twig, et retirer les classes
   `.event` et les attributs que seul l'ancien code utilisait.
3. Remplacer jQuery par du JavaScript natif. Les clics sur des éléments ajoutés
   dynamiquement se gèrent par délégation sur l'élément racine.
4. Retirer le code de l'ancien fichier et ses globaux devenus inutiles (commentaire
   `/* global */` en tête).
5. Écrire les tests vitest.
6. Lancer `composer build:prod` et commiter le bundle `public/assets/bundle/`,
   dans un commit séparé.
7. Parcourir à la main les pages concernées : il n'y a pas de tests de bout en bout.

## Suivi

- [x] Caisse (#615)
- [ ] `EntitySearchField` : le déplacer dans `assets/js/` et migrer les pages qui
  l'importent par URL
- [ ] `article_edit.js`
- [ ] Reste de `biblys-admin.js` : upload d'images et de fichiers, barre d'admin,
  abonnements
- [ ] `adm_reorder.js`, `adm_stocks.js`, `adm_publisher_stock.js`, `_list.js`
- [ ] Scripts inline des templates Twig et des controllers legacy
- [ ] `common.js` et jQuery
