# Historique des modifications

## 3.9.1 (DEV)

### Corrections

- La sélection d'un point de retrait Mondial Relay pouvait échouer pour les pays
  autres que la France. C'est corrigé.
- La page de réassort pouvait ne pas charger certaines collections. C'est
  corrigé.

## 3.9.0 (1er octobre 2025)

### Améliorations

- Une page "Ventes par article" a été ajoutée avec un filtre par année
- Il est possible d'exporter les ventes au format CSV depuis la page
  "Ventes par article"

## 3.8.3 (1er octobre 2025)

### Corrections

- L'ajout ou le retrait d'un contact à une liste Brevo pouvait provoquer une
  erreur. C'est corrigé.

## 3.8.2 (10 septembre 2025)

### Corrections

- La recherche et la création d'un contributeur depuis la page d'édition
  d'un article pouvaient échouer si l'utilisateur·ice avait des droits éditeur.
  C'est corrigé.

## 3.8.1 (28 août 2025)

### Corrections

- Les fichiers téléchargeables associés à un article ne s'affichaient pas
  correctement. C'est corrigé.
- La section "Contenu du lot" n'apparaissait sur la page d'édition d'un article
  lorsque le type "Lot" était sélectionné. C'est corrigé.
- La recherche de contributeur·ices pouvaient ne renvoyer aucun résultat si le
  nom ou le prénom contenait certains caractères accentués. C'est corrigé.

## 3.8.0 (6 août 2025)

### Améliorations

- La page d'accueil de l'administration affiche désormais un aperçu des derniers
  billets publiés sur le blog Biblys. Il est possible de les masquer en cliquant
  sur le bouton "Marquer comme lu".
- Le formulaire d'édition d'un article se modernise visuellement pour améliorer
  son ergonomie. C'est un chantier en cours. La moitié haute du formulaire a
  déjà été revue.

## 3.7.1 (23 juillet 2025)

### Corrections

* La page des ventes numériques pouvaient mettre du temps à s'afficher si
  le nombre de ventes était important. Elle affiche désormais par défaut
  uniquement les ventes du mois courant.
* La sélection d'un article depuis la page "Ajouter un exemplaire" ne
  déclenchait plus aucune action. Désormais, la sélection de l'article permettra
  d'aller à la page d'ajout d'exemplaire pour cet article.
* Le pré-remplissage des champs Titre et EAN sur la page d'édition d'article
  ne fonctionnait plus. C'est corrigé.
* La recherche automatique dans les bases externes n'était plus lancée lors de
  la création d'un nouvel article avec des champs pré-remplis. C'est corrigé.

## 3.7.0 (11 juillet 2025)

### Améliorations

* Il est désormais possible de créer de nouvelles zones tarifaires pour
  l'expédition et de modifier les pays associés à une zone tarifaire.

## 3.6.2 (18 juin 2025)

### Corrections

- Lorsqu'une commande Colissimo était marquée comme expédiée, le numéro de suivi
  n'était pas enregistré. C'est corrigé.

## 3.6.1 (11 juin 2025)

### Corrections

- La création d'un contributeur, d'une collection, d'un éditeur ou d'un cycle
  depuis la page d'édition d'un article pouvait échouer. C'est corrigé.

## 3.6.0 (6 juin 2025)

### Améliorations

- Il est maintenant possible de créer un extrait de billet de blog qui sera
  affiché à la place du contenu du billet sur les autres pages affichant le
  billet.
- Une page a été ajoutée à l'administration pour permettre de visualiser la
  bibliothèque numérique d'un·e utilisateur·ice.
- Une option d'exportation des commandes Colissimo a été ajoutée à la page
  d'administration des commandes. Elle permet d'exporter les commandes au
  format CSV pour les importer sur le site de Colissimo Entreprise.
- Le mode d'expédition "Suivi" a été renommé "Colissimo".
- La page d'ajout rapide d'exemplaire a été revue pour améliorer son
  ergonomie.

## 3.5.2 (16 avril 2025)

### Corrections

- La présence d'un paramètre fbclid ajouté par Facebook dans l'URL de certaines
  pages pouvait provoquer une erreur. C'est corrigé.

## 3.5.1 (9 avril 2025)

### Corrections

- Les commandes payées avec PayPal et PayPlug pouvaient ne pas être marquées
  comme payées. C'est corrigé.
- Le marquage d'une commande numérique comme expédiée pouvait échouer sans
  erreur. Ça n'arrivera plus.

## 3.5.0 (4 avril 2025)

### Expérience de paiement

- La page de choix d'un mode de paiement a été entièrement revue pour améliorer
  son ergonomie.
- Le formulaire de paiement par carte bancaire via Stripe est désormais intégré
  à la page de choix du mode de paiement de manière sécurisée, ce qui permet
  d'éviter une redirection vers le site de paiement.
- Il est désormais possible, au moment du paiement, de sauvegarder une carte
  bancaire pour pouvoir la réutiliser plus tard.

### Autres améliorations

- Les "tranches de frais de port" ont été renommées "option d'expédition".
- Des pages ont été ajoutées à l'administration pour afficher les pays de
  destination et les différentes zones tarifaires pour l'expédition.
- Un outil "Éditeurs" a été ajouté à l'administration.
- Une option de site `home_posts_limit` a été ajoutée pour limiter le nombre de
  billets de blog affichés sur la page d'accueil.
- Il est désormais possible d'insérer une espace insécable avec la combinaison
  de touche <kbd>Ctrl</kbd> + <kbd>espace</kbd> dans l'éditeur de texte enrichi.
- Lors de l'ajout d'un·e utilisateur·ice autorisé·e à gérer un éditeur, il n'est
  plus nécessaire que l'adresse e-mail corresponde à un compte existant. Si ce
  n'est pas le cas, le compte utilisateur sera créé automatiquement.

### Corrections

- Les pays suivants n'étaient pas affectés à la bonne zone tarifaire : Andorre,
  Monaco, Liechtenstein, Saint-Marin, Bulgarie, Croatie, Roumanie, Albanie,
  Biélorussie, Gibraltar, Macédoine, Moldavie. C'est corrigé.

### Instructions de mise à jour

#### Jouer les migrations

```shell
composer propel migrate
```

#### Stripe

Ajouter l'évènement `payment_intent.succeeded` à la liste des évènements à
transmettre au webhook endpoint de Biblys.

#### Swiper

La librairie "swiper" a été supprimée. Si votre thème utilise swiper, vous
devrez l'ajouter manuellement ou utiliser en remplacement le carrousel
Bootstrap :
https://getbootstrap.com/docs/4.6/components/carousel/#with-controls

## 3.4.3 (31 mars 2025)

### Corrections

- Certaines ventes pouvaient ne pas apparaître les page "Ventes numériques" et "
  Résultats par facture". C'est maintenant le cas.

## 3.4.2 (19 mars 2025)

### Corrections

- L'ajout d'un exemplaire à une liste pouvait déclencher une erreur. C'est
  corrigé.
- Certaines ventes pouvaient ne pas apparaître sur la page "Ventes par article
  en stock". C'est maintenant le cas.

## 3.4.1 (12 mars 2025)

### Corrections

- Les exemplaires en stock ajoutés depuis la mise à jour 3.4.0 pouvaient ne pas
  apparaître sur certaines pages. C'est corrigé.
- La caisse est désormais désactivée par défaut si la gestion de la TVA est
  activée, mais elle peut être réactivée temporairement.

## 3.4.0 (5 mars 2025)

### Améliorations

- Une page référençant les différents flux RSS a été ajoutée à l'adresse
  `/feeds/`.
- Un flux RSS des articles du catalogue est désormais disponible à l'adresse
  `/feeds/catalog.rss.xml`.
- Les flux RSS des actualités et des billets de blog affichent désormais des
  images de prévisualisation lorsqu'elles sont disponibles.
- Une nouvelle commande `users:create-admin` a été ajouter pour créer depuis la
  ligne de commande un nouvel utilisateur avec des droits d'admin.
- Il est désormais possible de supprimer un utilisateur.
- La page d'administration des utilisateurs dispose maintenant d'un champ de
  recherche et est paginée au-delà de 100 résultats.
- La déclaration des urls canoniques des pages a été améliorée.
- Un lien d'accès à la page de recherche a été ajouté dans le menu de navigation
  rapide.
- La présentation des options sur la page de recherche a été améliorée.

### Corrections

- Les billets de blog avec une date de parution dans le futur n'apparaissent
  plus dans le flux RSS.

### Instructions de mise à jour

#### Jouer les migrations

```shell
composer propel migrate
```

#### Font Awesome 6

Biblys utilise désormais la version de Font Awesome 6 contre la version 4
auparavant. Si votre thème utilise des icônes Font Awesome 4, vous devrez en
mettre à jour certaines en suivant les instructions de la page :
https://docs.fontawesome.com/web/setup/upgrade/upgrade-from-v4.

Notamment, remplacer :

- `fa fa-facebook-square` par `fa-brands fa-square-facebook`
- `fa fa-twitter-square` par `fa-brands fa-square-x-twitter`
- `fa fa-instagram` par `fa-brands fa-square-instagram`
- `fa fa-envelope` par `fa-solid fa-square-envelope`
- `fa fa-rss-square` par `fa-solid fa-square-rss`

## 3.3.2 (26 février 2025)

### Corrections

- Le flux RSS des actualités pouvait déclencher une erreur si un billet
  d'actualité n'avait pas de contenu. Désormais, les billets sans contenu sont
  ignorés lors de la génération du flux.
- La création d'un article attribué à un éditeur hors du filtre éditeur pouvait
  provoquer une erreur. C'est corrigé.
- Lors de l'import d'un article depuis nooSFere, les prix en euros n'étaient pas
  importés. Maintenant, ils le sont.

## 3.3.1 (19 février 2025)

### Corrections

- L'affichage plein écran d'une photo depuis la page de gestion d'un exemplaire
  était décalé. C'est corrigé.
- L'affichage plein écran d'une image ne fonctionnait plus depuis la page de
  commande. C'est corrigé.
- L'image de prévisualisation pour les réseaux sociaux ne s'affichaient plus
  sur les pages de campagnes de financement participatif. C'est corrigé.
- L'option `articles_per_page` n'était pas pris en compte sur la page de
  résultats de recherche et la page d'un éditeur. C'est maintenant le cas.

## 3.3.0 (5 février 2025)

### Améliorations

- Un flux RSS des billets de blog est désormais disponible à l'adresse
  `/feeds/blog.rss.xml`.
- Les pages d'administration s'affichent désormais dans un espace dédié
  indépendant de l'habillage graphique du site afin d'en faciliter
  l'utilisation.
- Le composant de pagination affiche désormais un menu plutôt qu'une liste de
  liens lorsqu'il y a plus de 10 pages.
- La recherche nooSFere requiert maintenant un terme de recherche d'au moins
  trois caractères.
- Une alerte de sécurité est envoyée par courriel à tous les admins lorsqu'un·e
  nouvel·le admin est ajouté·e.
- Une alerte de sécurité est envoyée par courriel à tous les admins lorsqu'un·e
  nouvel·le admin est ajouté·e.
- Des boutons pour modifier les contreparties ont été ajoutées sur la page d'une
  campagne de financement participatif.

### Corrections

- Lorsqu'une contrepartie de financement participatif était modifiée pour ne
  plus être en avant, elle pouvait apparaître en fin de liste plutôt qu'à sa
  place. C'est corrigé.
- Un billet hors-ligne pouvait être accessible via son URL. Ce n'est plus le
  cas.

### Instructions de mise à jour

#### Bootstrap 4

Biblys utilise désormais la version de Boostrap 4 contre la version 3
auparavant. Pour mettre à jour votre thème, vous pouvez vous référer à la page
https://getbootstrap.com/docs/4.0/migration/.

Notamment, remplacer :

- `btn-default` par `btn-light`
- `btn-xs` par `btn-sm`
- `hidden-*` par `d-*-none`
- les `navbar` : https://getbootstrap.com/docs/4.0/migration/#navbar

## 3.2.1 (8 janvier 2025)

### Corrections

- Lorsqu'une commande était marquée comme expédiée depuis la page de gestion des
  commandes, l'affichage de son statut n'était pas mis à jour. C'est corrigé.
- Lors de l'enregistrement d'une commande contenant des contreparties de
  financement participatif, la campagne correspondante n'était pas mise à jour.
  C'est maintenant le cas.
- Sur la page de gestion d'une offre spéciale, les collections et les articles
  pouvaient ne pas apparaître. Maintenant, ça marche.

## 3.2.0 (1er janvier 2025)

### Améliorations

- Biblys supporte désormais PHP 8.4
- Un outil de gestion des redirections a été ajouté à l'administration.

### Corrections

- Le total affiché dans le courriel de confirmation de commande n'incluait pas
  les frais de port. C'est corrigé.
- La validation d'une commande sans frais de port pouvait déclencher une erreur
  serveur. Désormais, l'utilisateur·ice sera redirigé vers le panier où il
  pourra sélectionner un mode d'expédition.

### Instructions de mise à jour

Après avoir procédé à l'installation de cette version…

#### Jouer les migrations

```shell
composer propel migrate
```

## 3.1.3 (26 décembre 2024)

### Corrections

- Le lien pour aller à la page d'un article depuis la bibliothèque numérique
  n'était pas bon. C'est corrigé.

## 3.1.2 (18 décembre 2024)

### Corrections

- Le bouton d'export des commandes pouvait apparaître même si le mode
  d'expédition Mondial Relay n'était pas activé. C'est corrigé.
- Il n'était pas possible de remplacer une illustration de billet de blog sans
  supprimer au préalable. C'est désormais possible.
- Les articles sans titres provoquaient l'affichage d'une ligne vide sur la
  page "Catalogue" de l'administration. Désormais, la mention "Article sans
  titre" est affichée.
- Les liens "billet précédent" et "billet suivant" pouvaient pointer vers des
  billets hors-ligne. Désormais, ils pointent uniquement vers des billets
  publiés.
- La création d'une demande de paiement Stripe pouvait échouer si le commande
  contenait un article gratuit. Maintenant, ça marche.

## 3.1.1 (11 décembre 2024)

### Corrections

- Le numéro de suivi n'était plus enregistré lors de l'expédition d'une
  commande (mais bien inséré dans le courriel de confirmation d'expédition).
  C'est corrigé.
- Certaines données étaient mal formatées dans lors de l'export des commandes au
  format CSV. Maintenant, c'est mieux.
- Le nom de famille est utilisé plutôt que le numéro client comme référence
  client lors de l'import en CSV.
- L'affichage d'une commande associé à un relais Mondial Relay invalide pouvait
  déclencher une erreur. Désormais, la mention "Point Relais inconnu" sera
  affichée.
- Une erreur survenant pendant l'enregistrement d'une commande pouvait résulter
  en une commande incomplète. Désormais, la commande est annulée si une erreur
  survient.
- Les articles de type "service" comme les abonnements pouvaient ne pas
  apparaître dans le panier. Ils apparaissent désormais dans une section dédiée.

## 3.1.0 (4 décembre 2024)

### Améliorations

- Un bouton "Exporter" a été ajouté sur la page des commandes. Il permet
  d'exporter la liste des commandes au format CSV, de manière à les importer
  ensuite dans l'interface Mondial Relay Connect.
- Lors d'une expédition avec Mondial Relay, le lien de suivi est désormais
  affiché sur la page de la commande et inclus dans le courriel de confirmation
  d'expédition.
- Un outil "Collections" a été ajouté à l'administration pour en faciliter la
  gestion.
- Il est désormais possible d'archiver des tranches de frais de port, afin
  qu'elle ne soit plus proposée lors de la création de nouvelles commandes.
- Il n'est plus possible de supprimer une tranche de frais de port si elle est
  utilisée par au moins une commande.
- De nouvelles méthodes `isBundle`, `isInABundle`, `getArticlesFromBundle` et
  `getBundles` ont été ajoutées à la classe `Article` pour faciliter l'affichage
  des lots d'articles (voir
  la [documentation](https://docs.biblys.fr/personnaliser/entites/article/#article-de-type-lot)).
- Une option de site `shipping_packaging_weight` permet de définir le poids
  d'emballage à ajouter au poids des articles pour le calcul des frais de port.
- Une option de configuration `mondial_relay.id_point_collecte` a été ajoutée.
  Elle permet de préciser le point relais dans lequel les colis Mondial Relay
  sont déposés pour être expédiés.
- Le champ "tome" des articles accepte désormais jusqu'à 12 caractères plutôt
  que 8.

### Corrections

- L'import depuis nooSFere d'un contributeur sans prénom pouvait déclencher une
  erreur. C'est corrigé.
- L'ajout d'une photo à un contributeur pouvait déclencher une erreur. Ce n'est
  plus le cas.
- L'ajout d'une chaine de caractère trop longue dans le champ "tome" d'un
  article pouvait déclencher une erreur. Désormais, c'est une erreur de
  validation qui sera affichée.

### Instructions de mise à jour

Après avoir procédé à l'installation de cette version…

#### Jouer les migrations

```shell
composer propel migrate
```

## 3.0.3 (20 novembre 2024)

Corrections

- L'import d'un article pouvait échouer si l'un·e de ses contributeur·ices
  existaient déjà avec un nom dont la graphie était légèrement différente. C'est
  corrigé.
- Lors de l'échec de la création d'une collection, le message pouvait ne pas
  s'afficher correctement. C'est maintenant le cas.

## 3.0.2 (13 novembre 2024)

Corrections

- Le choix d'un mode d'expédition pouvait échouer sans erreur. C'est corrigé.
- Les champs d'ajout d'image n'autorisaient que certains formats. Ils autorisent
  désormais tous JPEG, PNG ou WebP.
- La contrainte du nombre d'articles dans une commande pour une tranche
  tarifaire de frais de port n'était pas prise en compte. C'est maintenant le
  cas.

## 3.0.1 (6 novembre 2024)

Corrections

- Certaines illustrations de billet de blog pouvaient ne pas s'afficher. C'est
  corrigé.
- L'ajout d'un livre dans un lot pouvait échouer sans erreur. Maintenant, ça
  marche.

## 3.0.0 (30 octobre 2024)

### Licence libre

Biblys est désormais un logiciel libre !  
En savoir plus :
https://blog.biblys.fr/posts/biblys-est-desormais-un-logiciel-libre

- Ajout d'une licence AGPL-3.0.
- Ajout d'en-têtes de licence à chaque fichier du code source.

### Expédition avec Mondial Relay

- Biblys permet maintenant aux client·es de sélectionner un point de retrait
  Mondial Relay pour leur commande.
- La page de la commande affiche désormais le point de retrait sélectionné, s'il
  y a lieu.
- La page de gestion de frais de port permet de définir des tranches tarifaires
  pour l'expédition Mondial Relay.

Pour configurer Mondial Relay :
https://docs.biblys.fr/configurer/mondial-relay/

Le développement de cette fonctionnalité a été en partie financée par
les Éditions du Bélial'.

### Autres améliorations

- Les images (couvertures d'articles, photos d'exemplaires, illustrations de
  billets de blog, logo d'éditeurs, portraits de contributeur·ices,
  illustrations d'évènements) peuvent désormais être au format PNG ou WebP.
- La page "Espace disque" affiche désormais la taille occupée par les logos
  d'éditeurs, les portraits de contributeur·ices et les illustrations
  d'évènements.
- La commande `images:import` gère désormais les logos d'éditeurs, les portraits
  de contributeur·ices et les illustrations d'évènements.
- Une nouvelle commande `images:export` permet d'exporter les images de
  couverture des articles d'une collection précise.
- Sur la page d'édition d'un exemplaire, un nouveau bouton permet de marquer un
  exemplaire perdu comme retrouvé.
- Une commande `files:migrate` a été ajoutée pour migrer les fichiers de
  téléchargement des articles vers le nouveau répertoire.

### Instructions de mise à jour

Après avoir procédé à l'installation de cette version…

#### 1. Jouer les migrations

```shell
composer propel migrate
```

#### 2. Migrer les fichiers téléchargeables

```shell
composer files:migrate
```

#### 3. Importer les logos d'éditeurs, portraits de contributeur·ices et illustrations d'évènements

```shell
composer images:import publisher
composer images:import people
composer images:import event
```

## 2.86.3 (23 octobre 2024)

### Corrections

- L'affichage d'une galerie pouvait déclencher une erreur. C'est corrigé.
- L'ajout d'un média pouvait échouer si le dossier parent contenait des
  tirets ("-") dans son nom. Maintenant, ça marche.
- L'import de média pouvait échouer si le nom du fichier comportait une
  extension inhabituelle. Ça n'arrivera plus.
- Les dossiers et fichiers sont désormais triés par ordre alphabétique sur
  la page "Gestion des médias".

## 2.86.2 (16 octobre 2024)

### Corrections

- L'affichage d'un évènement publié par un utilisateur non authentifié pouvait
  déclencher l'affichage de la page de connexion. C'est corrigé.

## 2.86.1 (9 octobre 2024)

### Corrections

- Certains liens mal générés pouvaient déclencher des erreurs 404. C'est
  corrigé.
- La validation d'une commande contenant uniquement des articles téléchargeables
  pouvait déclencher une erreur. Ça n'arrivera plus.

## 2.86.0 (2 octobre 2024)

### Gestion des médias

- La présentation de la page "Gestion des médias" a été améliorée, avec
  notamment l'affichage de la taille de chaque fichier.
- Des sections "Billets de blog" et "Médias" ont été ajoutées à la page "Espace
  disque".
- Une commande `media:import` a été ajouté pour importer des médias depuis le
  répertoire public/media.
- La commande `images:import` gère désormais les illustrations de billets de
  blog.

### Expérience développeur

- La documentation d'installation locale de Biblys locale pour le
  développement ([INSTALL.MD](./INSTALL.md)) a été améliorée (#81 par @HEYGUL).
- Les pays de livraison et les langues sont désormais ajoutés à la base de
  données lors de l'installation de Biblys, afin d'éviter une manipulation
  manuelle (#83 et #88 par @HEYGUL).

### Déploiement

#### 1. Jouer les migrations

```shell
composer propel migrate
```

#### 2. Importer les médias

```shell
composer media:import
```

#### 3. Importer les illustrations de billets de blog

```shell
composer images:import post
```

## 2.85.2 (20 septembre 2024)

### Corrections

- La recherche d'exemplaire en stock avec un terme trop générique
  pouvait renvoyer un résultat trop grand et provoquer une erreur.
  Les résultats sont désormais limités à 100.
- Il était possible de donner des droits de gestion d'un éditeur
  à un utilisateur n'existant pas sur le site courant.
  Ce n'est plus possible.

## 2.85.1 (11 septembre 2024)

Correctifs

- La recherche d'une collection lors de la création d'un article pouvait
  déclencher une erreur. C'est corrigé.

## 2.85.0 (4 septembre 2024)

Améliorations

- Une nouvelle page individuelle permet de consulter les informations d'un·e
  utilisateur·ice, notamment ses méthodes de connexion.
- La page "Espace disque" affiche désormais la taille occupée par les
  fichiers téléchargeables.
- Une nouvelle commande `images:optimize` permet d'optimiser l'espace disque
  occupée par les images en limitant la taille de leur plus grand côté à
  2000 pixels.
- Biblys supporte désormais la plateforme de surveillance des erreurs et des
  performances Sentry. Pour l'activer, ajouter le paramètre `sentry.dsn` à
  la configuration.

Correctifs

- Le montant indiqué dans le courriel de confirmation de paiement d'une
  commande n'incluait pas les frais de port. C'est corrigé.

## 2.84.2 (28 août 2024)

Corrections

- La page du panier pouvait afficher une erreur s'il existait déjà une
  commande en cours. C'est corrigé.
- L'ajout d'un livre numérique gratuit à la bibliothèque pouvait déclencher
  une erreur. Maintenant, ça marche.

## 2.84.1 (14 août 2024)

Corrections

- Si un filtre éditeur est configuré, seuls les articles des éditeurs
  concernés seront comptabilisés sur la page Espace disque.
- Lors du paiement par chèque l'adresse d'expédition du chèque n'était plus
  affichée correctement. C'est corrigé.
- La photo sur la page de modification d'un exemplaire s'affichait en basse
  résolution lorsqu'elle était cliquée. Ce n'est plus le cas.
- Le script de réinitialisation de la base de données renvoyait une erreur.
  Maintenant, ça fonctionne.

## 2.84.0 (7 août 2024)

Améliorations

- La page "Espace disque" affiche désormais la taille occupée par les photos
  d'exemplaires.

Corrections

- L'option de paiement par virement pouvait ne pas s'afficher lorsque l'option
  du retrait en magasin était choisie. C'est corrigé.

## 2.83.1 (12 juillet 2024)

Corrections

- Certaines images, après envoi sur le serveur, pouvaient ne pas s'afficher.
  C'est corrigé.
- Une faille de sécurité affectant la gestion des listes d'exemplaires a
  été corrigée.
- La validation des paramètres de requête a été améliorée.

## 2.83.0 (5 juillet 2024)

Améliorations

- La validation d'une commande contenant des articles téléchargeables
  requiert maintenant l'acceptation des conditions spécifiques au numérique.
- Pour prévenir toute confusion, les articles physiques à expédier et les
  articles numériques à télécharger sont désormais clairement distingués
  dans le panier.
- Les livres affichés dans la bibliothèque numérique peuvent désormais être
  filtrées grâce à un champ de recherche.
- Les bibliothèques numériques contenant plus de 25 articles sont désormais
  paginées pour en faciliter l'exploration.
- Une page nouvelle page "Espace disque" dans l'administration permet de
  connaître l'espace disque occupé par les images (pour l'instant uniquement
  les images de couvertures des articles).

Déploiement

\1. Jouer les migrations

```shell
composer db:migrate
```

\2. Mettre à jour la configuration, en remplaçant les paramètres `media_path`,
`media_url` et `images_cdn` :

```yaml
media_path: public/images
media_url: /images/
images_cdn:
  service: weserv
```

par `images.path`, `images.base_url` et `images.cdn` :

```yaml
images:
  path: public/images
  base_url: /images/
  cdn:
    service: weserv
```

\3. Importer en base les images de couverture des articles

```shell
composer images:import
```

## 2.82.1 (26 juin 2024)

Corrections

Les validations des paramètres lors de l'envoi d'un courriel de connexion
pouvaient déclencher une erreur. C'est corrigé.

## 2.82.0 (5 juin 2024)

Utilisateurs locaux

- Les utilisateurs peuvent désormais s'inscrire et se connecter directement
  depuis le site, sans utiliser Axys, et mettre à jour leur adresse e-mail.
- Le fournisseur d'identité Axys est désormais facultatif.

Offres spéciales

- Les articles offerts dans le cadre d'une offre spéciale peuvent désormais
  être ajoutés ou retirés du panier par le client
- Les paniers contenant des articles liés à une offre spéciale font
  maintenant l'objet d'une validation au moment de la validation de commande.

Autres améliorations

- Un lien vers le panier apparaît désormais dans le menu supérieur, à côté
  du lien "Mon compte".
- Une page listant les utilisateur·ices a été ajouté à l'administration.

Déploiement

- Ajouter une chaine de 32 caractères aléatoire pour la valeur de l'optiond
  configuration `authentication.secret`.

## 2.81.2 (22 mai 2024)

Corrections

- Dans la liste des exemplaires, le client ayant acheté un exemplaire
  pouvait ne plus être affiché. C'est corrigé.
- La page des options pouvait laisser apparaître les options des
  utilisateurs. Ce n'est plus le cas.
- La mention "Déjà acheté" pouvait apparaître sur le panier pour des livres
  numérique n'ayant pas été acheté. C'est corrigé.

Déploiement

- Executer les migrations.

## 2.81.1 (15 mai 2024)

Corrections

De multiples problèmes liés à l'import des comptes utilisateurs Axys vers
des comptes utilisateurs locaux ont été corrigés.

## 2.81.0 (10 mai 2024)

## Utilisateurs locaux

- Les utilisateurs sont désormais importés depuis le fournisseur d'identité
  à la première connexion et enregistrées localement.
- Les échanges avec le fournisseur d'identité Axys se font désormais
  uniquement via le protocole OpenID Connect et sont conditionnés au
  consentement de l'utilisateur.

## Autres améliorations

- Une option de site `order_mail_subject_prefix` a été ajoutée. Elle permet
  d'ajouter un préfixe au sujet des courriels de confirmation de commande.
  Par exemple, si le préfixe est "YS |", le sujet du courriel sera "YS |
  Commande n°".

## 2.80.1 (8 mai 2024)

Corrections

- Les erreurs survenant lors de la gestion d'une commande dans
  l'administration pouvaient ne pas s'afficher correctement. C'est corrigé.

## 2.80.0 (1er mai 2024)

Améliorations

- Il est désormais possible dans le panier plusieurs offres simultanées.
- Lors de l'accès à une page nécessitant d'être authentifié, l'utilisateur
  est désormais redirigé vers la page de connexion plutôt que vers une page
  d'erreur.
- Des boutons pour ajouter ou supprimer des adresses dans une liste de
  contacts ont été ajoutés.
- Un lien vers le stock des articles a été ajouté sur la page Catalogue.

## 2.79.3 (1er mai 2024)

Corrections

- L'affichage d'une page de collection pouvait déclencher une erreur si
  l'option `articles_per_page` n'avait pas de valeur définie. C'est corrigé.

## 2.79.2 (17 avril 2024)

Corrections

- L'option `articles_per_page`, qui permet de spécifier le nombre d'articles
  à afficher sur une page, n'était pas pris en compte sur les pages de
  collection. C'est corrigé.

## 2.79.1 (10 avril 2024)

Corrections

- L'entrée de valeurs trop longues pour les codes BISAC, CLIC, Dewey et
  Electre pouvait déclencher une erreur. Une limite de 16 caratère a été
  ajoutée.
- Le lien vers la documentation des options de site a été mis à jour.

## 2.79.0 (3 avril 2024)

Offres spéciales

- Il est désormais possible de créer une offre spéciale pour afficher une
  information dans le panier du type "Un article offert pour deux livres de
  la collection X achetés".

Autres améliorations

- Une option de site "order_phone_required" a été ajouté pour rendre
  obligatoire le numéro de téléphone lors de la validation de commande.
- La validation des paramètres de requête lors de la recherche a été améliorée.
- Si renseigné, le numéro de téléphone du client apparaîtra désormais sur la
  page de la commande

## 2.78.4 (3 avril 2024)

Corrections

- Les erreurs lors de l'import pouvaient ne pas être affichées correctement.
  C'est réparé.

## 2.78.3 (25 mars 2024)

Corrections

- Une erreur pouvait survenir sur la page d'ajout d'exemplaire si une alerte
  était associé à un utilisateur supprimé. C'est corrigé.

## 2.78.2 (20 mars 2024)

Corrections

- L'état des livres d'occasion n'apparaissaient plus sur la facture. C'est
  corrigé.

## 2.78.1 (13 mars 2024)

Corrections

- Les erreurs lors de la création d'une nouvelle collection n'étaient pas
  affichées. C'est corrigé.

## 2.78.0 (6 mars 2024)

Améliorations

- Le panier visiteur est désormais rattaché au compte l'utilisateur au moment de
  la connexion.
- L'adresse e-mail du compte courant est désormais affiché sur la page "Mon
  compte".
- La page "Catalogue" de l'administration est désormais paginée et affiche 100
  articles par page.
- Les boutons "Confirmer la réception" et "Signaler un incident" sur la page
  de commande ont été remplacés par un lien vers la page de contact.

Déploiement

- Dans les controllers, remplacer `$_SITE`
  par `LegacyCodeHelper::getGlobalSite()`.

## 2.77.1 (21 février 2024)

Corrections

- L'adresse url du lien "Modifier ou annuler mes alertes" dans le courriel de
  notification des alertes était incorrecte. C'est corrigé.

## 2.77.0 (7 février 2024)

Améliorations

- Lors de la modification du prix d'un exemplaire vendu, le montant de la
  commande sera mis à jour si elle n'a pas encore été payée.
- Le bouton de confirmation de réception d'une commande a été supprimé.

## 2.76.0 (10 janvier 2024)

Améliorations

- Il est maintenant possible de suggérer aux clients d'ajouter des articles
  dans leur panier au moment de la validation de commande, grâce à l'option
  de site `cart_suggestions_rayon_id`.

## 2.75.3 (27 décembre 2023)

Corrections

- L'association d'un article à un billet de blog ne fonctionnait plus. C'est
  corrigé.
- Les anciennes urls des pages articles et contributeurs pouvaient renvoyer
  une erreur 404. Désormais, elles redirigent vers les urls des nouvelles pages.
- L'ajout d'un article à un lot pouvait échouer sans erreurs. Maintenant, ça
  marche.

## 2.75.2 (20 décembre 2023)

Corrections

- La page d'édition de billet ne s'affichait pas correctement. C'est corrigé.
- Les erreurs lors de l'import d'un article pouvaient ne pas être affichées
  correctement. C'est réparé.
- La création d'une liste d'exemplaires pouvait déclencher une erreur.
  Maintenant, ça marche.

## 2.75.1 (13 décembre 2023)

Corrections

- Le bouton permettant de modifier le pays au moment de la validation de
  commande n'était plus cliquable. C'est corrigé.
- Certains liens vers la page article n'étaient plus valide. Ils le sont à
  présent.

## 2.75.0 (5 décembre 2023)

Améliorations

- Deux nouvelles options `free_shipping_invite_text` et
  `free_shipping_success_text` ont été ajoutées pour personnaliser les
  textes sur le panier pour encourager les clients à atteindre le montant
  autorisant la gratuité des frais de port.
- Un utilisateur ne pouvait pas accepter une invitation de téléchargement
  si au moins l'un des articles associés était déjà dans sa bibliothèque.
  Désormais, les articles déjà présents seront ignorés et les autres seront
  ajoutés.
- Une page de documentation "Comment télécharger et lire des livres
  numériques" a été ajoutée.
- La fonctionnalité de mise à jour de Biblys via l'administration a été
  supprimée.

Corrections

- La pagination de la liste des invitations de téléchargement pouvait
  afficher un nombre de page plus important que le nombre de page réel.
  C'est corrigé.

Déploiement

- Remplacer `$request->query->get($key)`
  par `LegacyCodeHelper::getRouteParam($key)`
  dans les controllers legacy.

## 2.74.2 (DEV)

Corrections

- Le paiement via Stripe pouvait déclencher une erreur. C'est corrigé.
- Seul le premier article d'une invitation était validé au moment de
  l'acceptation de l'invitation. Désormais, ils le sont tous.

## 2.74.2 (2 décembre 2023)

Corrections

- Le paiement via Stripe pouvait déclencher une erreur. C'est corrigé.
- Seul le premier article d'une invitation était validé au moment de
  l'acceptation de l'invitation. Désormais, ils le sont tous.

## 2.74.1 (8 novembre 2023)

Corrections

- Une url d'article pouvait être considéré comme invalide si elle contenait
  le caractère "_". C'est corrigé.
- La page "Ma liste d'envies" pouvait s'afficher vide. Ce n'est plus le cas.

## 2.74.0 (1er novembre 2023)

Améliorations

- Il est désormais possible d'afficher sur la page panier une jauge
  encourageant le client à atteindre un certain montant pour bénéficier de
  la gratuité des frais de port. Le montant cible se configure à l'aide de
  l'option de configuration `free_shipping_target_amount`.
- Les paiements par virement sont désormais affichés sur la page récapitulative
  des commandes.
- Le champ permettant d'indiquer la raison de suppression d'un article a été
  rétiré.

Corrections

- L'ajout d'un nouvel administrateur déclenchait une erreur même si
  l'adresse email utilisée correspondait bien à un compte utilisateur. C'est
  corrigé.

## 2.73.1 (18 octobre 2023)

Corrections

- L'ajout d'un fichier téléchargeable à un article ne fonctionnait plus et
  échouait sans erreurs. C'est corrigé.
- Lorsqu'un client ajoutait des articles à une commande, le courriel de
  confirmation n'incluait pas les nouveaux articles. C'est maintenant le cas.

## 2.73.0 (6 octobre 2023)

Améliorations

- Les livres numériques peuvent maintenant être vendus avec tatouage
  numérique.
- Les titres d'articles triés alphabétiquement sont désormais affichés sans
  article sur la page de création d'invitation de téléchargement.
- Un avertissement est affiché sur la page Frais de port de l'administration
  pour les tarifs ne respectant pas la loi française.
- Une option de site "sales_disabled" a été ajoutée pour désactiver les
  ventes sur le site.
- Le formulaire de contact affichera désormais une erreur si le contenu du
  champ "Sujet" est long de moins de six caractères.
- L'ancien outil d'envoi de livres numériques a été supprimé.

Corrections

- Les erreurs lors de l'envoi ou de la mise à jour d'un fichier
  téléchargeable associé à un article n'était pas affiché. C'est désormais
  le cas.
- La page d'édition d'une commande pouvait déclencher une erreur. C'est corrigé.

## 2.72.2 (27 septembre 2023)

Corrections

- Si la recherche d'un article sur nooSFere échouait à cause d'une erreur,
  le message affiché était "Aucun résultat". Désormais, c'est le message
  d'erreur renvoyé par noosSFere.

## 2.72.1 (13 septembre 2023)

Corrections

- Le nom de domaine dans les liens d'invitations pouvait être incorrect.
  C'est corrigé.
- Le texte de prévisualisation du courriel d'invitation de téléchargement
  était un texte par défaut en anglais. Il a été remplacé par un texte en
  français.
- Des images n'existant pas pouvait parfois être affichées alors qu'elles
  n'existaient pas. Ce n'est plus le cas.

## 2.72.0 (6 septembre 2023)

Améliorations

La fonctionnalité d'envoi de livres numériques a été entièrement repensée,
l'outil s'appelle désormais "Invitations de téléchargement"

- Les livres ne sont plus ajoutés directement à la bibliothèque de
  l'utilisateur, celui-ci reçoit par courriel un lien permettant de faire
  l'ajout au compte de son choix.
- Il est possible de récupérer le lien créé pour faire un envoyer un
  courriel personnalisé plutôt que d'utiliser le courriel générique envoyé
  par le site.
- Il est également possible de faire une création en lien en masse pour un
  grand nombre d'utilisateurs, puis de télécharger un fichier CSV avec les
  liens, pour faire par exemple un envoi avec un outil d'e-mailing.

Autres améliorations

- Il est désormais possible d'utiliser Brevo pour gérer la liste de contacts.
- L'affichage de prévisualisation lors de la publication d'un lien vers un
  site Biblys sur les réseaux sociaux a été améliorée.
- Il n'est plus possible d'inviter un utilisateur à être administrateur s'il
  ne possède pas un compte au préalable.
- L'erreur affichée lors du refus de la connexion avec Axys a été rendue
  plus explicite.

## 2.71.2 (31 août 2023)

Corrections

- La recherche d'un client depuis la caisse pouvait échouer. C'est corrigé.
- L'affichage d'un livre sans prix pouvait déclencher une erreur sur la page
  catalogue de l'administration. Ce n'est plus le cas.
- L'affichage des erreurs au moment de la connexion avec Axys a été améliorée.

## 2.71.1 (24 août 2023)

Corrections

- Un certain nombre d'outils d'administration pouvait renvoyer des erreurs
  suite à une migration de données partielles. C'est corrigé.

## 2.71.0 (21 août 2023)

Améliorations

- Cette mise à jour essentiellement technique permet une importante
  migration des données des comptes Axys afin de préparer la possibilité de
  créer un compte directement sur le site.
- Il n'est plus possible d'inviter un utilisateur à être administrateur s'il
  ne possède pas un compte au préalable.

## 2.70.2 (16 août 2023)

Corrections

- Le message "Vous n'êtes pas autorisé à accéder à cette page." pouvait
  apparaître pour un utilisateur avec des droits d'administration. C'est
  corrigé.
- Le vidage des paniers pouvait déclencher une erreur SQL. Ça n'arrivera
  plus.
- La génération des termes de recherche d'un article pouvait déclencher une
  erreur si la chaine de caractères était trop longue. Désormais, elle est
  limitée à 1024 caractères.
- Le téléversement d'un fichier depuis la page Gestion des médias échouait
  dans certains cas. Maintenant, ça marche.

## 2.70.1 (9 août 2023)

Corrections

- Le menu d'administration pouvait ne pas apparaître. C'est corrigé.

## 2.70.0 (2 août 2023)

Améliorations

- L'outil de suivi des conversions a été supprimé.
- L'option "biblys" du gestionnaire de liste de contacts a été supprimée.

Déploiement

- Exécuter les migrations

## 2.69.3 (2 août 2023)

Corrections

- Une erreur 404 pouvait survenir lors de l'ajout d'un nouvel article ou
  l'accès au catalogue en tant qu'éditeur. C'est corrigé.

## 2.69.2 (26 juillet 2023)

Corrections

- Une erreur "Votre maison d'édition n'est pas autorisée sur ce site."
  pouvait apparaître sur la page d'édition d'article pour les
  administrateurs. C'est corrigé.
- Une alerte créée sans prix maximum pouvait déclencher une erreur sur la
  page d'ajout d'un exemplaire au stock. Ça n'arrivera plus.

## 2.69.1 (20 juilet 2023)

Corrections

- Une erreur pouvait survenir lors de l'affichage de l'éditeur de commande
  pour une commande dont le pays de livraison n'était pas renseigné. C'est
  corrigé.
- L'option de site `old_controller` pour la page d'accueil pouvait
  déclencher une erreur 404. A présent, la page d'accueil s'affiche.
- L'affichage de la page d'édition article pouvait échouer avec une erreur.
  Ce n'est plus le cas.

## 2.69.0 (5 juillet 2023)

Améliorations

- La protection anti-spam du formulaire de contact a été renforcée avec
  l'ajout d'un mécanisme de type "honey pot".

Déploiement

- Dans les controllers, remplacer `$_LOG`
  par `LegacyCodeHelper::getGlobalVisitor()`.
- Dans les controllers, remplacer `$_SITE`
  par `LegacyCodeHelper::getGlobalSite()`.
- Dans les controllers, remplacer `$site`
  par `LegacyCodeHelper::getGlobalSite()`.
- Remplacer liens vers `adm_article` par `article_edit`.

## 2.68.6 (14 juin 2023)

Corrections

- Certaines dates n'apparaissaient pas dans le filtre "Ajoutés le" sur la
  page des stocks. C'est corrigé.
- L'affichage de la page d'édition d'article pouvait échouer pour un article
  associé à un rayon supprimé. C'est corrigé.

## 2.68.5 (9 juin 2023)

Corrections

- La redirection vers la page d'accueil après la connexion pouvait échouer.
  C'est corrigé.
- L'affichage de la facture d'une commande à laquelle n'était associé aucun
  pays de livraison déclenchait une erreur. C'est corrigé.

## 2.68.4 (27 mai 2023)

Corrections

- Un problème de variable indéfini empêchait le bon fonctionnement de la
  page d'édition des billets.

## 2.68.3 (17 mai 2023)

Corrections

- La journalisation des notices de dépréciation pouvait créer des journaux très
  lourds très rapidement. Elle est désormais désactivée par défaut.

## 2.68.2 (10 mai 2023)

Corrections

- Les rayons pouvaient ne pas correctement s'afficher sur la page d'édition
  d'un article. C'est corrigé.
- L'accès anonyme à une page de commande pouvait déclencher une erreur.
  Désormais, c'est l'invite à s'identifier qui s'affiche.

## 2.68.1 (7 mai 2023)

Corrections

- La dépendance du gestionnaire d'erreur Rollbar pouvait déclencher une
  erreur fatale. C'est corrigé.

## 2.68.0 (3 mai 2023)

Améliorations

- Biblys ne supporte plus PHP 8.0. La version minimale requise est PHP 8.1.
- La durée des sessions créées lors d'une connexion à Axys a été allongée
  quand la case "Se souvenir de moi" est cochée.
- Mailjet peut désormais être utilisé comme gestionnaire de liste de contacts.
- L'option de paiement "Échange" a été ajoutée.
- Un numéro de version a été ajouté aux images d'illustrations des billets
  afin de contourner la mise en cache lorsqu'elles sont mises à jour.
- L'affichage des messages flash d'information, de succès, d'avertissement
  et d'erreur a été améliorée.

Deploiement

- Utiliser PHP 8.1
- Ajouter `{% include "layout:_flash_messages.html.twig" %}` au fichier de
  layout `base.html.twig`, juste après l'inclusion
  du partiel `_overall_menu.html.twig`.
- Dans les controllers, remplacer `$_V` par `getLegacyVisitor()`.
- Executer les migrations (supprimer au préalable les colonnes `cart_gift…`)

## 2.67.3 (26 avril 2023)

Corrections

- Le raccourci clavier "copier" pouvait parfois déclencher le raccourci
  d'accès rapide à la caisse. C'est corrigé.
- Une erreur pouvait empêcher le bon affichage de la page "Mes souhaits".
  C'est corrigé.

## 2.67.2 (19 avril 2023)

Corrections

- Le message d'erreur affiché lors de l'utilisateur d'un critère de tri
  invalide était peu clair. C'est corrigé.
- L'échec de la connexion à la base de données n'arrêtait pas l'execution.
  C'est désormais le cas.
- La validation des adresses des sites webs d'éditeurs exigeaient qu'elles
  commencent par "http://". À présent, "https://" est aussi accepté.
- L'envoi d'un livre numérique à une adresse e-mail invalide échouait
  silencieusement. Désormais, un message d'avertissement est affiché.
- Les images de couvertures et photos d'exemplaires pouvaient ne pas
  s'afficher sur la page panier. C'est corrigé.

## 2.67.1 (12 avril 2023)

Corrections

- Une erreur pouvait se produire lors de l'ajout d'un·e contributeur·trice à
  un article. C'est corrigé.
- La section "Numérique" de l'administration est masquée si l'option de site
  `downloadable_publishers` n'est pas renseignée.
- Les options de site sont désormais supprimées lorsque leur valeur est vidée.

## 2.67.0 (5 avril 2023)

Améliorations

- Le widget Axys a été remplacé par des liens de connexion, d'inscription et
  un menu utilisateur local.
- Le moteur de suggestions Gleeph a été ajouté.
- L'outil de statistiques Umami a été ajouté.
- Les outils de statistiques ignorent désormais les visiteurs qui sont
  identifiés comme des administrateurs.
- Après la connexion, l'utilisateur sera désormais redirigé vers la page où
  il se trouvait lorsqu'il a cliqué sur le bouton "Connexion".
- L'outil d'envoi de newsletter a été supprimé.

Déploiement

- Ajouter `{% include "layout:_overall_menu.html.twig" %}` au fichier de layout
  base.html.twig, juste après l'ouverture de la base `<body>`

## 2.66.3 (27 mars 2023)

Corrections

- Le code du tracker de suivi Matomo Analytics n'était plus inséré, même si
  l'option de configuration correspondant été présente. C'est corrigé.

## 2.66.2 (15 mars 2023)

Corrections

- Les fichiers javascripts pouvaient ne pas être importés dans certains
  controllers dépréciés. C'est corrigé.
- L'auto-complétion des contributeurs depuis la page d'édition des articles
  ne fonctionnaient plus pour les utilisateurs avec des droits éditeurs.
  C'est réparé.
- La présence d'un article supprimé dans une commande pouvait déclencher une
  erreur. Désormais, la mention "Article inconnu" s'affichera à la place du
  titre.

## 2.66.1 (8 mars 2023)

Corrections

- L'affichage d'un inventaire pouvait échouer pour les articles avec une
  quantité de 0. C'est corrigé.
- La création d'un paiement Paypal pouvait déclencher une erreur. C'est réparé.
- L'accès à une page statique inexistante pouvait déclencher une erreur
  serveur. C'est maintenant une erreur 404.

## 2.66.0 (1er mars 2023)

Améliorations

- Lorsqu'un contributeur est modifié, les mots-clés des articles associés
  seront désormais rafraîchis.
- L'outil de support intégré a été déprécié
- Un lien a été ajouté dans l'administration vers la nouvelle plateforme
  Améliorer Biblys

Corrections

- Un problème d'affichage pouvait affecter le bon fonctionnement de l'outil
  d'inventaire. C'est corrigé.

Déploiement

- L'option de site permettant d'utiliser une page statique comme page
  d'accueil s'écrit désormais `page:{slug}` plutôt que `page:{id}`.

## 2.65.4 (15 février 2023)

Corrections

- Le masquage des éléments lors de l'impression d'une facture ne
  fonctionnait plus, c'est corrigé.

## 2.65.3 (1 février 2023)

Corrections

- Le lien pour le suivi Colissimo ne fonctionnait plus. C'est corrigé.
- Une erreur "RuntimeException" pouvait s'afficher lors de l'affichage de la
  page d'édition d'un contributeur. Ça n'arrivera plus.
- Une erreur pouvait survenir lors de l'ajout d'un administrateur.
  Maintenant, ça marche.

## 2.65.2 (21 janvier 2023)

Corrections

- L'option d'affichage d'un rayon sur la page d'accueil provoquait un
  dédoublement de l'en-tête du site. C'est corrigé.
- Une erreur "WARNING" du mode développement pouvait parfois apparaître en
  mode production. Ça n'arrivera plus.

## 2.65.1 (11 janvier 2023)

Corrections

- L'invite à la connexion lors du téléchargement d'un livre numérique
  gratuit déclenchait une erreur. C'est corrigé.
- Le message d'échec du test captcha lors de l'inscription à la newsletter
  ne s'affichait pas correctement. C'est mieux.

## 2.65.0 (4 janvier 2023)

Améliorations

- Biblys ne supporte plus PHP 7.4. La version minimale requise est PHP 8.0.
- Les urls des pages articles et contributeurs dépréciées sont désormais
  préfixées par /legacy/a/ et /legacy/p/

Déploiement

- Utiliser PHP 8.0.

## 2.64.3 (23 décembre 2022)

Corrections

- L'édition d'un modèle du thème pouvait renvoyer une erreur. Ce n'est plus
  le cas.
- Sur la page des stocks éditeur, le nombre de livre en stock n'était plus
  mis à jour lorsque le champ était déselectionné. C'est corrigé.

## 2.64.2 (16 décembre 2022)

Corrections

- Les erreurs "Requête invalide" (HTTP 400) étaient mal prises en compte.
  C'est réparé.

## 2.64.1 (11 décembre 2022)

Corrections

- Lors de l'accès non-authentifié à la page d'une commande associée à un
  utilisateur, une erreur pouvait s'afficher. Désormais, c'est la page
  invitant à la connexion.
- L'accès à la page d'édition d'un billet pouvait provoquer une erreur
  "Type Post inconnu". C'est corrigé.

## 2.64.0 (2 décembre 2022)

Améliorations

- La page Contributeur·trice affiche désormais la photo si elle est disponible.
- Le message d'erreur lors de l'échec d'un paiement via Paypal est désormais
  plus explicite.
- Une option "mode" a été ajoutée à la configuration Paypal pour pouvoir
  choisir d'activer le mode "sandbox" ou le mode "live" (par défaut)

## 2.63.1 (23 septembre 2022)

Corrections

- Sur certaines pages avec pagination, le retour à la page précédente ne
  fonctionnait plus. C'est corrigé.

## 2.63.0 (2 septembre 2022)

Améliorations

- Les résultats de recherche peuvent désormais être triés par date de
  parution croissante ou décroissante.
- Le moteur de recherche accepte désormais des mots-clés à partir d'un
  caractère (contre trois auparavant).
- Une option de configuration permettant l'activation du mode maintenance
  avec un message a été ajoutée, elle remplace l'option de site équivalente.
- Lorsqu'une alerte est envoyée à une adresse e-mail invalide, l'erreur
  affichée est désormais plus claire et n'empêche plus l'envoi des autres
  alertes.

Corrections

- La page "Chiffre d'affaires" de l'administration ne s'affichait pas
  correctement. C'est corrigé.

Déploiement

- Biblys ne supporte plus PHP 7.3. La nouvelle version minimale requise est 7.4.

## 2.62.2 (22 juillet 2022)

Corrections

- L'erreur "Cet ISBN est déjà utilisé par un autre article" pouvait
  apparaître lors de la création d'un article pour de mauvaises raisons.
  C'est corrigé.
- Certains boutons et fonctionnalités de la page de gestion des stocks
  étaient parfois ignorées. C'est réparé.

## 2.62.1 (12 juillet 2022)

Corrections

- Une erreur pouvait empêcher l'utilisation des anciens modèles principaux.
  C'est corrigé.

## 2.62.0 (8 juillet 2022)

Améliorations

- Le modèle principal du thème utilise désormais le langage Twig.
- Les vues des thèmes utilisent désormais des blocks de manière à pouvoir
  utiliser différentes modèles principaux en fonction du contexte.
- Un filtre "Articles en stock uniquement" a été ajouté à la page des
  résultats de recherche.

Corrections

- Les illustrations des billets ne s'affichaient pas sur la page d'accueil.
  C'est corrigé.

## 2.61.0 (6 mai 2022)

Améliorations

Cette mise à jour apporte le support de la nouvelle version d'Axys basée sur
le standard OpenID Connect.

## 2.60.2 (15 avril 2022)

Correction

La suppression d'un livre pouvait provoquer une erreur. C'est corrigé.

## 2.60.1 (8 avril 2022)

Corrections

- Une erreur pouvait empêcher la page Mise à jour de s'afficher correctement.
  Elle a été corrigée.
- Le calcul du temps restant avant la fin d'une campagne de financement
  participatif pouvait être faussé quand il restait exactement un mois. Il
  est maintenant correct.
- Le champ "Client n°" sur la page des ventes permettrait d'entrer du texte.
  On ne peut maintenant entrer qu'un identifiant numérique.

## 2.60.0 (1er avril 2022)

Améliorations

- Une page "Paiements", avec des filtres par date et mode de paiement a été
  ajoutée à l'administration.
- Deux colonnes "Auteur·trice·s" et "Stock" ont été ajoutées à la page
  Catalogue et à la fonctionnalité d'export en CSV.
- Les performances de la page Catalogue dans l'administration ont été
  améliorées.

Corrections

- L'ajout d'un rayon à un article pouvait parfois provoquer l'apparition du
  message d'erreur "Cet éditeur ne fait pas partie des éditeurs autorisés".
  C'est corrigé.

## 2.59.3 (25 mars 2022)

Correction

Une erreur est empêchait l'utilisateur de la page ”Ventes numériques” de
l'administration. C'est corrigé.

## 2.59.2 (18 mars 2022)

Correction

La création d'une alerte pouvait échouer silencieusement si l'utilisateur
n'était pas authentifié. Un message d'erreur est désormais affiché.

## 2.59.1 (11 mars 2022)

Corrections

- Une erreur pouvait s'afficher sur la page de gestion des raccourcis de
  l'administration. C'est corrigé.
- Un problème pouvait empêcher de valider un panier sur un site ne proposant
  qu'un seul mode d'expédition. Ça n'arrivera plus.
- La barre d'administration pouvait apparaître pour un utilisateur qui
  n'avait pas les droits d'adminstration. Ce n'est plus le cas.
- Une erreur pouvait empêcher un éditeur d'ajouter des mots-clés à un
  article ou de les supprimer. Maintenant, ça marche.

## 2.59.0 (4 mars 2022)

Améliorations

- Une option de site "alerts" a été ajoutée pour activer l'envoi d'alertes
  par courriel lors de l'ajout d'exemplaires au stock.
- Une option de site "countries_blocked_for_shipping" a été ajoutée pour
  permettre de bloquer l'expédition vers certains pays.
- Une vérification a été ajoutée à la création de contreparties de
  financement pour empêcher l'association d'articles inexistant.
- Les titres des articles de la page Catalogue de l'administration sont
  désormais cliquables.
- L'affichage du chemin d'accès des fichiers sur la page Gestion des médias
  a été amélioré.

Corrections

- Les quantités de contreparties d'une campagne de financement participatif
  terminée pouvait être mises à jour. C'est corrigé.
- L'annulation d'une commande contenant un exemplaire associé à une campagne
  de financement participatif pouvait déclencher une erreur. Plus maintenant.

## 2.58.4 (20 février 2022)

Correction : l'enregistrement d'un modèle de thème pouvait échouer sans
erreur dans certain cas. C'est corrigé.

## 2.58.3 (11 février 2022)

Corrections

- Plusieurs dates identiques pouvaient apparaître dans les raccourcis de la page
  "Ventes par article". C'est corrigé.
- L'erreur affichée lors de l'enregistrement d'un modèle était laconique.
  C'est amélioré.
- L'enregistrement du fichier de styles CSS pouvait déclencher une erreur 500.
  Ce
  n'est plus le cas.

## 2.58.2 (4 février 2022)

Corrections

- Le sélecteur de pays de la page de confirmation de commande ne s'affichait pas
  correctement. C'est mieux.
- L'affichage de la page d'accueil pouvait déclencher une erreur si aucune
  option n'était configuré. Ce n'est plus le cas.
- L'enregistrement d'une commande sans frais de port pouvait déclencher une
  erreur. Ça fonctionne à présent.

## 2.58.1 (4 février 2022)

Corrections

- Certaines configurations de page d'accueil pouvaient déclencher une erreur à
  cause d'une dépendance manquante. C'est corrigé.
- Le formattage d'une date pouvait déclencher une erreur sous certaines
  conditions. Les pendules ont été remises à l'heure.

## 2.58.0 (28 janvier 2022

Améliorations

- Le formulaire d'enregistrement de l'adresse d'expédition lors de
  l'enregistrement d'une commande a été amélioré, notamment pour mieux
  distinguer les champs obligatoires des champs facultatifs.
- Un lien vers la page des conditions générales de vente a été ajouté au
  courriel de confirmation de commande.

## 2.57.4 (28 janvier 2022)

Cette mise à jour purement technique ajoute une option de configuration
nécessaire pour préparer une migration de la base de données.

## 2.57.3 (21 janvier 2022)

Correction : une erreur empêchait l'afffichage de la page de gestion d'un rayon
dans l'administration. C'est corrigé.

## 2.57.2 (16 janvier 2022)

Correction : un utilisateur pouvait accéder à la page d'édition d'un billet de
blog sans être identifié. C'est corrigé.

## 2.57.1 (16 janvier 2022)

Correction : l'accès à la page de rédaction d'un billet de blog en tant
qu'éditeur provoquait une erreur. C'est corrigé.

## 2.57.0 (7 janvier 2022)

Améliorations

- La page "frais de port" de l'administration sont désormais triés par type,
  zone et montant pour permettre une lecture plus facile.
- Cette mise à jour apporte également un certain nombre de modifications
  purement techniques visant à améliorer la stabilité et la maintenabilité de
  Biblys, tout en préparant la refonte prochaine du panier.

## 2.56.3 (24 décembre 2021)

Correction : l'information selon laquelle une commande avait été validée avec
le mode d'expédition "retrait en magasin" était mal enregitrée. C'est rétabli.

## 2.56.2 (19 décembre 2021)

Correction: Une commande pouvait être enregistrée avec des frais de port à 0 €
si le client double-cliquait sur le bouton de validation. C'est corrigé.

## 2.56.1 (19 novembre 2021)

Corrections

- L'accès en tant qu'éditeur à la page de gestion des billets ne fonctionnait
  plus. C'est rétabli.
- Il était possible d'ajouter au panier un livre en cours de réimpression. Ce
  n'est plus le cas.

## 2.56.0 (5 novembre 2021)

Améliorations

- Des liens vers les pages Facebook et comptes Twitter apparaissaient désormais
  sur les pages contributeurs lorsque leurs adresses sont précisées.

Corrections

- Un e-mail était envoyé lors de l'annulation d'une vente magasin. C'est
  corrigé.

## 2.55.4 (29 octobre 2021)

Corrections

- La création d'un contributeur déjà existant échouait sans afficher d'erreur.
  C'est corrigé.
- La raison pour laquelle une page 404 était affichée n'était plus visible par
  les administrateurs. C'est maintenant le cas.
- L'import noosfere pouvait échouer lorsque les contributeurs étaient associés
  à certains rôles inconnus de Biblys. Maintenant, ça marche.

## 2.55.3 (22 octobre 2021)

Corrections

- L'ajout d'un contributeur à un article pouvait parfois échouer sans message
  d'erreur. Ça n'arrivera plus.
- L'ajout d'un rayon à un article pouvait parfois provoquer l'apparition du
  message d'erreur "Cet éditeur ne fait pas partie des éditeurs autorisés".
  C'est corrigé.
- Sur Safari, le rôle choisi pour un contributeur ne s'affichait pas
  correctement lors de l'édition d'un article. C'est maintenant le cas.

## 2.55.2 (17 octobre 2021)

Corrections

- Un bug empêchait les utilisateurs avec les droits de gestion pour un éditeur
  de gérer les contributions associé à cet éditeur. C'est corrigé.
- Les liens vers les pages évènements déclenchaient une erreur 404. Ce n'est
  plus le cas.

## 2.55.1 (9 octobre 2021)

Corrections

- L'erreur affichée lorsqu'on tentait d'afficher un article associé à un
  contributeur supprimé était peu claire. Maintenant, c'est mieux.
- La page Rayons de l'administration ne s'affichait plus du tout. C'est
  corrigé.
- L'erreur affichée lorsqu'une collection était créée avec un nom déjà utilisé
  était sybilline. Elle est désormais plus bavarde.

## 2.55.0 (1er octobre 2021)

Améliorations

- Il est désormais possible de préciser le genre à utiliser pour les
  contributeurs ("féminin", "masculin" ou "neutre")
- Le genre par défaut est désormais "neutre" et plus "masculin".

## 2.54.2 (17 septembre 2021)

Corrections

- Les erreurs provoquées lors de l'association d'un article et d'un rayon
  s'affichent désormais correctement
- L'ajout d'un rayon à un article en cours de création ne provoque plus
  d'erreurs
- La page Frais de port de l'administration affiche uniquement les frais
  de port du site en cours

## 2.54.1 (10 septembre 2021)

Corrections

- Ajout des identifiants nécessaires pour la migration de la base de données
- L'affichage des résultats sur l'ancienne page des résultats de recherche
  fonctionne à nouveau

## 2.54.0 (3 septembre 2021)

Cette mise à jour apporte un certain nombre de modifications purement
techniques visant à améliorer la stabilité et la maintenabilité de Biblys,
tout en préparant la refonte prochaine du panier.

## 2.53.8 (27 août 2021)

Corrections

- La mise à jour du fichier CSS via l'éditeur de thème fonctionne à nouveau
- Le filtrage des billets par catégorie fonctionne à nouveau

## 2.53.7 (30 juillet 2021)

Corrections

- L'erreur affichée lors de l'ajout au panier d'un livre pour lequel aucun
  exemplaire n'est disponible a été améliorée.

## 2.53.6 (22 juillet 2021)

Corrections

- L'appel d'une page paginée avec un numéro de page ne provoque plus d'erreur
  serveur

## 2.53.5 (10 juillet 2021)

Corrections

- L'appel d'une page paginée avec un numéro de page inférieur à zéro ne
  provoque plus une erreur serveur

## 2.53.4 (2 juillet 2021)

Corrections

- L'association d'un article à un éditeur filtré ou à une collection appartenant
  à un éditeur filtré est à nouveau possible, mais affiche une erreur claire.
- La recherche d'un client par nom ne provoque plus d'erreur

## 2.53.3 (25 juin 2021)

Corrections

- L'ajout d'un exemplaire dans une liste n'envoie plus plusieurs requêtes
- Le message affiché lors d'une demande de remboursement ne mentionne plus la
  gratuité des frais de port
- Il est à nouveau possible de retirer un exemplaire d'une liste immédiatement
  après l'y avoir ajouté
- Les éditeurs filtrés n'apparaissent plus dans les propositions lors de
  l'association d'une collection à une fiche article

## 2.53.2 (18 juin 2021)

Corrections

- Les utilisateurs avec des droits sur un éditeur ne peuvent plus gérer cet
  éditeur sur un site sur lequel l'éditeur n'est pas autorisé.
- Le message d'erreur affiché lorsqu'un nouvel article est soumis avec un code
  ISBN invalide a été amélioré
- Le message d'erreur affiché lorsqu'un nouvel article est soumis avec une url
  déjà existante a été amélioré

## 2.53.1 (11 juin 2021)

Corrections

- Le système de mise à jour automatique fonctionne à nouveau
- Les articles sans image couverture s'affichent correctement
- L'ajout d'un article à la liste d'envies par un utilisateur non authentifié
  affiche un message d'erreur

## 2.53.0 (4 juin 2021)

Cette mise à jour apporte un certain nombre de modifications purement
techniques visant à améliorer la stabilité et la maintenabilité de Biblys,
tout en préparant la refonte prochaine du panier.

## 2.52.4 (21 mai 2021)

Corrections

- Le calcul des frais de port ne provoque plus d'erreurs serveur lorsque appelé
  avec de mauvais paramètres
- L'affichage d'une version de Biblys inexistante provoque une erreur 404
  plutôt qu'une erreur serveur
- Les performances de la page « Historique des mises à jour » ont été améliorées
- La réception d'une notification d'un remboursement PayPlug ne provoque plus
  une erreur serveur

## 2.52.3 (14 mai 2021)

Corrections

- L'utilisation d'un mauvais paramètre de tri dans une liste de
  résultats de recherche ne renvoie plus une erreur serveur.
- Le message d'erreur lors de l'ajout d'un article dans un rayon dans lequel
  cet article est déjà s'affiche désormais correctement.

## 2.52.2 (16 avril 2021)

Corrections

- La présence d'une adresse e-mail invalide dans les abonnés ne bloque plus
  l'envoi de la newsletter
- L'envoi d'une newsletter avec un nom de campagne fonctionne à nouveau
- L'utilisation de l'ancienne page de recherche renvoie vers la nouvelle plutôt
  que d'afficher une erreur
- La création d'un client depuis la caisse fonctionne à nouveau

## 2.52.1 (9 avril 2021)

Corrections

- L'erreur affichée lors de l'accès à un article via son ISBN en utilisant un
  ISBN invalid a été améliorée
- L'affichage d'une erreur lorsqu'un éditeur est créé avec un nom déjà utilisé a
  été améliorée
- L'affichage d'une erreur lors de l'export du stock vers Place des Libraires
  fonctionne à nouveau
- L'ajout d'un exemplaire à une liste est à nouveau possible
- La page d'ajout au stock s'affiche à nouveau correctement
- L'ajout d'un livre au panier depuis la caisse fonctionne à nouveau
- Les erreurs de validation lors la modification d'un cycle s'affichent
  correctement
- L'erreur 404 s'affiche à nouveau correctement lorsqu'un visiteur accède à la
  page d'une série inexistante
- L'export du stock vers Place des Libraires fonctionne à nouveau

## 2.52.0 (2 avril 2021)

Améliorations

- Ajout d'un champ pour ajouter un logo à une fiche éditeur
- Le bouton "Vider tous les paniers" a été supprimé
- Les redirections de pages causées par un changement d'url utilisent désormais
  le statut HTTP 301 plutôt que 302.
- L'erreur affichée sur la page de contact a été améliorée

Corrections

- Le statut HTTP des pages d'erreur 404 est bien 404.

## 2.51.5 (26 mars 2021)

Correction d'une erreur qui pouvait conduire à l'affichage d'une erreur 404
après l'authentification via Axys.

## 2.51.4 (19 mars 2021)

Corrections

- L'url d'un article contient bien le nom de l'auteur et plus "anonyme"/ si
  l'article a un ou plusieurs auteurs associés.
- Le compte du nombre d'éditeurs est désormais correct si le site utilise un
  filtre éditeurs
- Le formulaire d'édition d'un article fonctionne à nouveau si le site n'a aucun
  rayon

## 2.51.3 (5 mars 2021)

Correction : le raccourci "Ajouter au stock" de la barre d'administration
fonctionne à nouveau.

## 2.51.2 (26 février 2021)

Corrections

- L'accès au tableau de bord en tant qu'éditeur fonctionne à nouveau
- Il n'est plus possible d'associer un article à un cycle supprimé

## 2.51.1 (18 février 2020)

Corrections

- Les erreurs serveur survenant lors de l'import d'un article s'affichent à
  nouveau correctement
- L'import d'un article associé à un contributeur ayant précédemment été
  supprimé fonctionne à nouveau

## 2.51.0 (5 février 2021)

- Amélioration des performances
- Frais de port : il est désormais possible de préciser un montant minimum
  pour les conditions d'une tranche de frais de port (utile par exemple pour
  offrir les frais de port à partir d'un certain montant de commande).
- Paiement : lorsque la création d'un paiement PayPlug échoue, le message
  d'erreur affiché est désormais plus explicite.
- Commande : l'adresse e-mail indiquée par le client est désormais validée
  avant l'enregistrement de la commande
- Commande : si l'utilisateur est identifié, le champ Adresse e-mail sera
  pré-remplie avec l'adresse e-mail de son compte utilisateur lors de
  l'enregistrement d'une nouvelle commande

## 2.50.3 (26 janvier 2021)

- Correction : cliquer sur annuler dans la boîte de dialogue du numéro de suivi
  d'une commande ne marque plus la commande comme expédiée.

## 2.50.2 (15 décembre 2020)

Corrections

- La création ou la modification d'une tranche de frais de port avec un montant
  de 0,00 € fonctionne à nouveau.
- L'outil de gestion des frais de port affiche désormais correctement les
  tranches sans commentaires.

## 2.50.1 (14 octobre 2020)

Corrections

- L'échec d'un paiement via PayPlug ne cause plus d'erreur
- La modification des options "Exempté de TVA" et "Livres vendables sur
  commande" des fournisseurs fonctionnent à nouveau

## 2.50.0 (7 octobre 2020)

Améliorations

- Ajout de deux options `shipped_mail_subject` et `shipped_mail_message`
  permettant de personnaliser le courriel de confirmation d'expédition d'une
  commande
- Le sujet du courriel de confirmation d'expédition d'une commande à retirer en
  magasin est désormais "Commande disponible en magasin" plutôt que "Commande
  expédiée".
- Ajout d'une page pour gérer les comptes autorisés à gérer un éditeur

## 2.49.4 (30 septembre 2020)

Corrections

- L'écriture des journaux d'erreurs fonctionne à nouveau pour l'authentification
  les paiements par Paypal

## 2.49.3 (23 septembre 2020)

Correction d'un problème qui pouvait conduire, dans certains cas, à l'apparition
des rayons associés à un article depuis un autre site à apparaître sur les
fiches articles.

## 2.49.2 (15 septembre 2020)

Corrections

- L'import d'un article associé à une collection déjà existante fonctionne à
  nouveau
- Le retrait d'un rayon associé à un article fonctionne à nouveau
- L'import d'un article dont le champ auteurs dépasse 256 caractères ne provoque
  plus d'erreur
- La mise à jour d'un contributeur existant ne provoque plus d'erreur
- Le tronquage d'une chaîne de caractères utf-8 est calculé au nombre de bits et
  plus au nombre de caractères.

## 2.49.1 (7 septembre 2020)

Corrections

- La case à cocher "À la une" du formulaire d'édition d'un billet peut à nouveau
  être décochée.
- Le numéro de version d'une image de couverture est correctement inclus dans
  les urls du CDN WeServ
- L'ajout d'une valeur dépassant 256 caractères dans le champ auteurs ne
  provoque plus d'erreur

## 2.49.0 (2 septembre 2020)

Améliorations

- Biblys supporte désormais le prestataire de paiement Stripe
- Ajout d'une page listant tous les éditeurs
- Un exemplaire neuf ajouté à un panier ne peut plus être ajouté à un autre
  panier pendant une heure après l'ajout, afin d'éviter le "vol" involontaire
  d'exemplaire lorsque de nombreuses commandes sont validées au même moment.
- Ajout d'une option de configuration pour utiliser le CDN Weserv
- Suppression du champ "Civilité" lors de l'enregistrement d'une commande
- Ajout d'un mode maintenance
- Ajout de Pinterest aux boutons de partage
- Retrait de Google+ des boutons de partage

Déploiement

- Ajouter `{AXYS_MENU}` dans `layout.html.twig` avant `</body>`

## 2.48.1 (10 juillet 2020)

Corrections

- Les livres indisponibles n'apparaissent plus comme disponibles dans la liste
  des alertes s'il existe un exemplaire supprimé
- Les contributeurs supprimés n'apparaissent plus sur certaines fiches livres
- La suppression d'un compte administateur fonctionne à nouveau
- La suppression d'un panier fonctionne à nouveau

## 2.48.0 (5 juin 2020)

Améliorations

- Meilleures compression et mise en cache des fichiers CSS et JavaScript
- L'interface de gestion des administrateurs a été améliorée
- Désactivation de l'envoi de courriel lors de l'ajout d'un livre numérique
  gratuit à la bibliothèque
- Ajout d'une option de site `downloadable_publishers` pour définir les éditeurs
  pour lesquels le téléchargement d'articles numérique est autorisé
- Ajout d'attributs aria dans la bibliothèque numérique pour faciliter son
  utilisation avec des lecteurs d'écrans
- L'affiche de la pagination fonctionne à nouveau pour les billets de blog
- Le lien d'une page contributeur vers la page de son pseudonyme fonctionne
  à nouveau
- Il est désormais possible de supprimer un dossier et son contenu dans le
  gestionnaire de médias

Corrections

- La case d'abonnement à la newsletter lors du téléchargement d'un livre
  numérique
  gratuit n'est plus cochée par défaut
- La suppression de tous les paniers fonctionne à nouveau
- Amélioration des messages d'erreur lors d'une tentative de téléchargement d'un
  fichier téléchargeable en accès restreint.
- La génération de l'adresse de retour lors de la connexion Axys fonctionne
  à nouveau
- Les médias uploadés s'affichent correctement dans l'administration

Déploiement:

- Déplacer `/public/:site/media/` vers `

```console
mkdir -p app/public/theme \
  && mv public/custom.css app/public/theme/styles.css \
  && composer theme:refresh \
  && vim app/Resources/views/layout.html \
  && cp -r public/$(basename "$PWD")/media public/media
```

And add `<link rel="stylesheet" href="/theme/styles.css?{ASSETS_VERSION}" />`
to `layout.html.twig`'s head section

## 2.47.1 (4 mars 2020)

- Les frais de port globaux fonctionnent à nouveau

## 2.47.0 (1er mars 2020)

Améliorations

- Refonte de la page de gestion des frais de port
- Ajout un lien vers Matomo dans l'administration
- Ajout d'un filtre "Sans poids" à la page des stocks
- Ajout des rayons au suivi e-commerce de Matomo
- Ajout d'une option de suppression d'un éditeur
- Les alertes sont désormais supprimées lorsque l'article correspondant est
  acheté (#145).
- Les pictogrammes de disponibilité d'un exemplaire sont désormais plus lisibles

Corrections

- Le panier propose uniquement le mode d'expédition le moins cher pour chaque
  type d'envoi
- La suppression du champ item d'un article fonctionne à nopuveau
- L'association d'un article à un autre via le chant "Lier à l'article"
  fonctionne à nouveau
- Les actions "Supprimer" et "Retourner un exemplaire" fonctionnent à nouveau

## 2.46.0 (2 février 2020)

Améliorations

- Biblys gère à présent l'outil de statistiques open-source Matomo
- Ajout d'une option `payment_check` pour activer le paiement par chèque
- Les boutons de connexion et d'inscription sur la page panier sont désormais
  plus visibles
- Les prix d'origine des livres en promotion sont désormais affichés sur la page
  des stocks

Correction

- Tous les liens vers la page de contact ont été corrigés
- Les récompenses littéraires apparaissent à nouveau sur le formulaire d'édition
  d'un article

Déploiement

- Ajouter l'option de site `payment_check` si le site accepte les paiements par
  chèque
- Configurer Matomo

## 2.45.0 (1er décembre 2019)

Améliorations

- Ajout d'une option de site `assets_version` permettant d'ajouter un numéro
  de version aux appels des fichiers CSS & Javascript, évitant ainsi la
  réutilisation d'une ancienne version mise en cache.
- Amélioration de la pagination des listes de billets de blog
- Amélioration de la pagination des listes d'évènements
- Ajout de la possibilité de changer la couleur du panier lorsqu'il est plein

Corrections

- Les numéros de suivi d'expédition sont désormais correctement sauvegardés
- Les pages des éditeurs absents de la liste des éditeurs autorisés
  n'apparaissent plus
- L'ajout d'une image à un évènement fonctionne à nouveau
- La création d'une nouvelle page fonctionne à nouveau
- Le filtrage par rayon de l'extrait d'inventaire fonctionne à nouveau

## 2.44.0 (5 octobre 2019)

Améliorations

- Ajout d'un bouton "Charger plus de commandes" sur la page de gestion des
  commandes
- Ajout d'un bouton "Annuler le retour et remettre en vente" sur la fiche
  d'un exemplaire retourné

Corrections

- Les articles associés à des exemplaires supprimés apparaissent à nouveau dans
  les résultats de recherche
- L'histoire de modifications d'un éditeur fonctionne à nouveau avec les
  utilisateurs supprimés

## 2.43.1 (3 août 2019)

- Correction d'un problème d'installation des composants

## 2.43.0 (3 août 2019)

Améliorations

- Ajout d'une épreuve anti-spam lors de l'inscription à la newsletter
- Les rayons associables à un article sont désormais triés alphabétiquement

Corrections

- Toutes les langues sont à nouveau disponibles dans l'éditeur de fiche article
- La création d'un nouveau cycle depuis l'éditeur de fiche article fonctionne
  à nouveau
- Le calcul des frais de port en fonction du montant de la commande fonctionne
  à nouveau
- Les rayons supprimés n'apparaîssent plus dans l'éditeur de fiche article
- Les éditeurs s'affichent correctement sur les pages de mot-clé
- Seules les collections des éditeurs autorisés sont désormais proposés dans
  l'éditeur de fiche article

## 2.42.0 (2 juin 2019)

Améliorations

- PHP 7.2 est désormais la version minimale requise pour Biblys
- Ajout d'un type d'article "Carterie"
- Le temps restant avant la fin d'une campagne de financement participatif
  s'affiche désormais en jours, heures, minutes, puis secondes.
- Les campagnes de financement participatif commencent à minuit et se terminent
  à 23h59.

Corrections

- Les articles "Hors-commerce" ne peuvent plus être ajoutés au panier
- Les adresses des pages statiques sont désormais correctement générées
- La création d'un nouvel éditeur lors de la création d'un article
  fonctionne à nouveau
- L'auteur d'un billet de blog publié par un éditeur s'affiche à nouveau
- Le message d'erreur lors de l'ajout au panier d'un exemplaire sans
  poids s'affiche correctement
- Les exemplaires supprimés n'apparaissent plus dans les résultats de l'ancien
  moteur de recherche des librairies
- Le message d'erreur correct s'affiche lors de l'accès non-autorisé à une
  facture
- L'administration s'affiche correctement si aucun raccourci n'a été créé

## 2.41.0 (2 avril 2019)

Améliorations

- Refonte de la gestion des tâches planifiées (cron)
- Ajout d'un journal des tâches planifiées dans l'administration
- Refonte de la tâche planifiée pour l'export du stock vers Place des libraires
- Ajout d'une option pour utiliser Biblys CDN pour les images
- Biblys est maintenant entièrement compatible avec PHP7

Corrections

- Correction d'un problème de validation des ISBN lors de l'enregistrement
  d'une fiche article
- Correction d'un problème de génération des mots-clés d'un article
- Correction d'un problème de génération de l'url d'une collection
- Correction d'un problème affectant la récupération de la liste des factures
  existantes lors de l'ajout d'un exemplaire
- Correction d'un problème affectant l'ajout d'une image à un nouveau billet
- Correction d'un problème affectant le formulaire de sélection d'un moyen
  de paiement
- Correction d'un problème affectant l'ajout d'une contrepartie de financement
  participatif avec une quantité illimité
- Correction d'un problème d'affichage des dates dans Safari sur la page
  de gestion des commandes
- Correction d'un problème de selection d'une collection en mode éditeur
- Correction d'un problème de l'affichage de l'erreur pour une page de
  contributeur non existant
- Correction du fonctionnement du bouton "Afficher plus de résultats" lors de
  l'import d'un article d'une base externe
- Correction d'un problème de double requête lors de l'import d'un livre

Déploiement

- Configurer Biblys Cloud CDN
- Créer la table `cron_jobs`
- Supprimer la table `crons`
- Supprimer les tables `bb_forums`, `bb_posts`, `bb_topics`
- Supprimer les tables `biblio`, `credits`, `depenses`, `epubs`, `keywords`
  `Messages`, `noosfere`, `types`
- Ajouter le champ `stock_photo_version` à la table `stock`

## 2.40.0 (2 mars 2019)

- Refonte de la gestion de la configuration SMTP
- L'envoi de newsletters se fait désormais via SMTP
- Les utilisateurs ont désormais la possibilité de réutiliser l'adresse
  d'une précédente commande lors de l'enregistrement d'une nouvelle
- Ajout d'un champ "Nom de campagne" aux envois de newsletter
- Ajout d'un numéro de version aux urls des couvertures des livres pour pallier
  les problèmes de mise en cache
- Amélioration de l'affichage et du signalement des messages d'erreur
- Ajout d'un bouton "remettre en vente" à la fiche exemplaire
- Ajout d'une option de configuration pour définir le port du serveur MySQL
- Optimisation de la requête MySQL de l'ancienne page des résultats de recherche
- Correction d'une coquille sur la page du choix de paiement
- Correction d'un problème affectant les adresses sauvegardées dans le compte
  utilisateur
- Correction d'un problème de protocole dans l'url de retour après un paiement
  PayPal
- Amélioration de l'affichage des erreurs dans l'outil de gestion des commandes

Déploiement:

- Configurer SMTP
- Configurer CDN images
- Table `sites`: supprimer les champs ne commençant pas par site\_
- Table `sites`: retirer les champs de la configuration SMTP
- Table `prices`: renommer le champ `price_date` en `price_created`
- Table `prices`: ajouter les champs `price_updated` et `price_deleted`
- Créer la table `cron_jobs`

## 2.39.0 (26 janvier 2019)

- Refonte de la présentation des évènements
- Ajout d'un outil pour ajouter tous les articles d'un certain type à un rayon
- Ajout d'une option de site pour ajouter une notice sur les factures
- Ajout d'un bouton pour accéder à une commande depuis un exemplaire vendu
- Les anciennes urls d'articles sont désormais redirigés vers les nouvelles
- Correction d'un problème d'affichage du pays sur certaines anciennes commandes
- Correction d'un problème affectant l'ajout d'une contrepartie de financement
  participatif illimité au panier
- Correction d'une faille de sécurité dans le panier
- Lors de l'ajout d'un article au panier, sont désormais ajoutés en priorité
  les exemplaires qui ne sont pas déjà dans le panier d'un autre client.
- Correction d'un problème affectant l'éditeur de thème sur d'anciennes versions
  de
  Safari

## 2.38.0 (25 novembre 2018)

- Ajout des champs "Impression" et "Lectorat" à la fiche article
- Ajout de liens billets suivant/précédent sur les billets
- Ajout d'une option de site 'payment_iban' pour préciser un numéro de compte
  et activer le paiement par virement
- Correction du lien de signalement des erreurs
- Correction d'un problème de référencement de la page panier dans les moteurs
  de recherche

## 2.37.0 (29 septembre 2018)

- Ajout des champs "Accroche" et "Notice biographique" à la fiche article
- Les champs "Quatrième de couverture", "Sommaire", "Bonus", "Accroche" et
  "Notice bibliographique" sont désormais masqués lorsqu'ils sont vides.
- Une nouvelle option de site permet de définir une opération spéciale avec un
  article offert pour un nombre d'articles achetés au sein d'une collection.
- Les adresses en https:// sont désormais reconnues dans le champ site web de
  la fiche éditeur
- Correction d'un problème lors de l'envoi au serveur d'une image au format PNG
- Correction d'un bug laissant apparaître des billets supprimés dans le flux RSS

## 2.36.1 (6 juillet 2018)

- Ajout des mentions RGPD lors de l'inscription à la newsletter
- Correction d'une faille de sécurité sur la page éditeurs
- Correction de l'ordre de tri par défault de l'ancienne liste des résultats
  de recherche
- Correction d'un bug empêchant les éditeurs d'ajouter un rayon ou un mot-clé
  à un article
- Correction d'un faux positif lors de la vérification d'unicité d'un ISBN
  à cause d'articles supprimés
- Correction d'un bug laissant apparaître des articles supprimés lors de
  l'ajout rapide d'exemplaires

## 2.36.0 (25 mai 2018)

- Ajout d'une option pour définir un message à ajouter aux courriels des alertes
- Ajout d'une option pour définir le repertoire de stockage des images
- Amélioration de l'outil de génération des termes de recherches lorsqu'il est
  utilisé avec un grand nombre de titres
- Amélioration l'importation nooSFere pour des livres référencés avec un ISBN-10
- Les pieds de page des factures ne sont plus répétés à chaque page lors de
  l'impression
- Ajout d'un message d'avertissement sur la caisse pour les assujetis à la TVA
- Ajout d'un message d'avertissement RGPD sur l'outil de newsletter
- Correction d'un bug affectant le calcul du nombre d'articles dont les termes
  de recherches doivent être regénérés
- Correction d'un bug affectant le changement de nom d'une collection
- Correction d'un bug qui laissait apparaître des contributeurs supprimés
- Correction d'un bug qui laissait apparaître des alertes supprimées
- Correction d'un bug affectant l'orientation des photos d'exemplaires
- Correction d'une faille de sécurité dans les options de tri de l'ancien
  moteur de recherche
- Correction d'une faille de sécurité sur la page contributeur
- Correction d'un bug qui laissait apparaître des articles supprimés dans
  l'ancien moteur de recherche

## 2.35.0 (27 avril 2018)

- Refonte des galeries photos
- Ajout du prix dans le catalogue éditeur exportable
- Correction d'un bug laissant apparaître des articles supprimés lorsqu'ils
  étaient liés
  aux articles
- Correction d'un bug lors de l'accès à une fiche article depuis le formulaire
  de
  modification
- Correction un bug affectant l'ajout au panier sur Internet Explorer

## 2.34.1 (17 février 2018)

- Correction d'un bug affectant la redirection après création ou modification
  d'un article

## 2.34.0 (27 janvier 2018)

- Ajout d'une page d'administration listant tous les articles avec une option
  d'export
  au format CSV
- Correction d'un bug affectant la création d'adresse unique pour les articles
- Correction d'un bug affectant l'affichage d'un message d'erreur lors de
  l'ajout d'un
  livre non disponible au panier

## 2.33.1 (12 janvier 2018)

- Correction d'une faille XSS dans l'ancienne page de recherche
- Suppression de l'ancienne page de contact

## 2.33.0 (31 décembre 2017)

- Refonte de la gestion des conversions
- Les remises peuvent maintenant être des nombres décimaux
- La page contact peut désormais avoir un sujet prédéfini
- Correction d'un problème lors de l'ajout d'une photo d'exemplaire
- Correction d'un bug lors de l'association d'un éditeur à un article
- Correction d'un problème affectant l'affichage du tableau de bord éditeur

## 2.32.0 (24 novembre 2017)

- Ajout d'un type de reliure "Integra"
- Ajout d'une option de site "collection_filter_hide" pour masquer des
  collections
- Le panier liste désormais les livres triés par date d'ajout (#121).
- Les mots-clés s'affichent désormais par ordre alphabetique sur les fiches
  articles (#129).

## 2.31.1 (10 octobre 2017)

- Correction d'un bug qui permettait de créer des mots-clés vides
- Correction d'un bug affectant la modification d'une collection
- Correction d'erreurs 404 apparaissant lors de l'accès à certains billets

## 2.31.0 (28 octobre 2017)

- Refonte de la gestion des valeurs par défaut
- Ajout de la possibilité de définir des mots-clés par défaut pour les articles
- Ajout d'une bannière de consentement des cookies
- Ajout d'une option de site google-analytics-id
- Ajout de liens vers toutes les pages dans la pagination de liste
- Correction de l'ajout d'un mot clé existant avec une apostrophe
- Correction d'un problème de récupération du prix lors de l'import nooSFere

## 2.30.0 (30 septembre 2017)

- Ajout d'une page listant tous les auteurs d'un catalogue
- Ajout d'une page listant toutes les récompenses littéraires
- Ajout d'une adresse courte /isbn/{isbn} pour les articles
- Ajout d'une option pour afficher les derniers billets d'une catégorie sur la
  page d'accueil
- Ajout des champs Photo Site web, Page Facebook et Compte Twitter dans le
  nouveau formulaire de modification des contributeurs
- Refonte de la page Chiffre d'affaires par fournisseur
- Correction d'un bug affectant le filtrage des billets supprimés
- La case 'À la une' est désormais correctement cochée si le billet est à la une
- Ajouter d'un bouton 'réduire' pour replier un texte dépliable
- Correction d'un problème d'ajout d'un rayon à un nouvel article
- Correction d'un problème d'accès à la page de gestion des évènements

## 2.29.0 (10 juin 2017)

- Refonte de la gestion des fournisseurs des éditeurs
- Suppression de l'ancienne interface d'administration
- Correction d'un bug affectant le téléchargement de fichier sur Safari iOS
- Correction d'un bug affectant l'ajout de mots-clés sur une fiche article
  vierge
- Correction du calcul de la TVA des articles de type 'Jeu de rôle'
- Correction d'un bug affectant la suppression des billets
- Correction d'un bug affectant les notifications pour les termes de recherche

## 2.28.1 (17 mars 2017)

- Correction : le poids est également affiché sur les pages de facture
- Correction : les contributeurs ne sont plus tous ajoutés au champ auteurs
- Correction d'un problème affectant le calcul du chiffre d'affaires d'occasion

## 2.28.0 (27 février 2017)

- Ajout d'un champ "Commentaires" au formulaire de commande
- Amélioration de la lisibilité du formulaire de validation de commande
- Ajout de la mention "Mise à dispo. en magasin" plutôt que "Expédiée" sur les
  commandes à retirer en magasin (#112)
- Ajout d'une option pour ajouter un dégradé sur les textes dépliables
- Tous les types de contributeurs d'un article sont désormais ajoutés à ses
  termes de recherche (#115).
- Refonte de l'outil d'actualisation des termes de recherche
- Refonte de l'outil d'envoi des livres numériques
- Envoi des livres numériques : ajout d'option pour ne pas envoyer de courriel
  ou ne pas créer de compte utilisateur
- Amélioration des messages de confirmation sur la page de gestion des paniers
- Page de commande : les liens de téléchargements ne sont plus proposés pour
  les articles qui ne sont pas téléchargeables
- Ajout d'un outil d'export des abonnés à la newsletter

## 2.27.1 (10 février 2017)

- Correction d'un problème affectant l'importation de fiches noosSFere
- Correction d'un problème affectant le générateur de codes ISBN

## 2.27.0 (29 janvier 2017)

- Refonte de l'interface d'administration
- Ajout d'une barre de raccourcis pour les administrateurs
- Ajout d'un outil pour ajouter un exemplaire à un panier depuis sa fiche
- Ajout de l'emplacement lors du choix d'un exemplaire à ajouter à une liste

## 2.26.1 (14 janvier 2017)

- Correction d'un problème d'affichage des paniers web dans l'administration

## 2.26.0 (31 décembre 2016)

- Refonte du système de notifications
- Refonte de la page Suivi des conversions
- Information du suivi de conversions sur la page de la commande
- Ajout de la possibilité de supprimer un article
- Ajout d'un modèle 'Page d'accueil (derniers articles parus)' à l'éditeur
  de thèmes
- Ajout d'un modèle 'Formulaire de contact' à l'éditeur de thèmes
- Affichage d'une erreur si un fichier média présent sur le disque ne possède
  pas d'entrée équivalente dans la base de données
- Affichage d'une erreur si la valeur de l'option de site `admin_entries` est
  mal formatée
- Ajout de façonnage 'relié sous jaquette'
- Utilisation des couleurs officielles pour les boutons de partages sociaux
- Amélioration du message de confirmation lors de l'ajout d'une collection
  entière à un rayon
- Correction : l'adresse de la page d'un rayon est désormais mise à jour
  lorsque le nom du rayon est mis à jour
- Correction : les collections supprimées ne bloquent plus le réassort
- Correction d'un bug affectant l'ajout des articles d'une collection à un rayon
- Correction : le bouton 'Panier vide' n'envoie plus les formulaires
- Correction : l'éditeur de thème ne fige plus le bouton panier
- Correction d'un bug affectant la création d'un nouveau rayon
- Correction : les rayons supprimés ne sont plus proposés sur la fiche article
- Correction : utilisation du protocole correct dans la gestion des médias

## 2.25.3 (3 décembre 2016)

- Correction d'un problème de mise à jour automatique des composants

## 2.25.2 (2 décembre 2016)

- Correction d'un problème d'affichage de la nouvelle interface d'administration

## 2.25.1 (2 décembre 2016)

- Correction d'un problème de redirection dans le mécanisme de mise à jour

## 2.25.0 (2 décembre 2016)

- Un administrateur peut désormais modifier le mode d'expédition d'une commande
  après son enregistrement
- Ajout d'un aperçu de la nouvelle future interface d'administration

## 2.25.0 (DEV)

- Ajout d'une option `weight_required` pour définir un poids minimum pour les
  exemplaires à la création et à l'ajout au panier
- Ajout de la possibilité de supprimer une collection si elle n'a aucun
  article associé
- Ajout d'un journal de sécurité pour les tentatives de connexion via l'API
- Amélioration du journal et du signalement des erreurs serveur
- Les composants sont désormais mis à jour automatiquement pendant la mise
  à jour automatique de Biblys
- Ajout d'un test anti-spam à la nouvelle page de contact
- Correction d'un problème d'affichage sur les pages commande et facture avec
  l'option de site `publisher_filter`
- Correction d'un bug qui permettait d'associer un article à une collection
  qui avait été supprimée

## 2.24.0 (4 novembre 2016)

- Refonte du formulaire de contact
- Ajout d'un composant pour rendre dépliable un texte trop long
- Ajout d'un champ format à la fiche Article
- Ajout des façonnages "broché avec rabats", "cartonné", "livret" et
  "reliure japonaise"
- Ajout de la pagination aux pages Rayons
- Ajout d'une nouvelle page de résultats de recherche
- Refonte des pages Contributeur
- Refonte des pages Collection
- Amélioration de la validation des informations lors de la création et de la
  modification des entités dans la base de données
- Ajout des balises OpenGraph à la nouvelle fiche article
- Ajout des balises Twitter Cards à la nouvelle fiche article
- Ajout d'une option de site `name_for_check_payment` pour personnaliser le
  nom auquel les chèques doivent être libellés pour le paiement des commandes
- Le téléchargement n'est plus forcé pour les fichers en accès public
- Ajout de la possibilité de définir des tranches de frais de port en fonction
  du montant de la commande
- Ajout d'un champ Pays d'origine à la fiche Article
- Ajout des options de site `home_preview_text` et `home_preview_image` pour
  définir le texte et l'image à afficher sur Twitter et Facebook pour l'accueil
- Correction d'un bug affectant la création d'articles sur un site avec le
  filtre éditeur activé
- Correction : ajout de la croix dans le bouton de fermeture des
  fenêtres modales

## 2.23.0 (21 octobre 2016)

- Refonte de la gestion des mots-clés associés à un article
- Refonte de la gestion des rayons associés à un article
- Utilisation de HTTPS pour les images de couvertures et autres médias
- Ajout d'une option de site pour afficher un rayon en page d'accueil
- Ajout d'une option `articles_per_page` pour définir le nombre d'articles à
  afficher par page
- Ajout d'une option de site `publisher_filter` pour filter l'affichage des
  articles par éditeur sur tout le site
- Correction d'un bug empêchant l'apparition des prix littéraire immédiatement
  après leur création dans le formulaire de fiche article
- Correction d'un problème qui empêchait l'affichage de la page Inventaires
- Correction d'un problème affectant l'ajout de mot clé avec apostrophe
- Correction d'un problème affectant le choix d'un mode de paiement lorsqu'une
  commande se trouvait sur la dernière ligne

## 2.22.0 (1 octobre 2016)

- Ajout d'une option de configuration pour forcer le protocole HTTPS
- Ajout d'une option de site pour afficher une offre promotionnelle dans
  le panier
- Ajout d'un filtre par type d'article sur la page de gestion des codes ISBN
- Ajout d'une option de site `isbn_checker_start` pour définir la valeur de
  départ du générateur d'ISBN libres du catalogue éditeur
- Correction d'un bug affectant l'association de mots clés à un article

## 2.21.0 (9 septembre 2016)

- Affichage d'un message d'erreur si aucun mode de paiement n'est sélectionné
  n'est sélectionné.
- Correction d'un problème d'affichage de la date de fin sur la page des
  ventes numériques
- Correction d'un problème d'affichage des frais de port sur certaines commandes
- Correction d'un bug qui permettait de créer des listes sans titre

## 2.20.0 (5 juin 2016)

- Amélioration de l'affichage des erreurs de validation HTML dans le code d'une
  page statique
- Amélioration du message d'erreur lors de la création d'un nouveau contributeur
  déjà existant
- Correction d'un problème affectant le champ "Prix sauvegardé" de la fiche
  exemplaire
- Correction : l'exemplaire disparaît après suppression depuis la page des
  stocks

## 2.19.0 (21 mai 2016)

- Ajout des modèles Pages d'accueil et Page d'accueil (derniers billets)
  à l'éditeur de thèmes
- Ajout d'un menu déroulant pour sélectionner la priorité d'un ticket de support
- Correction d'un bug lors de l'ajout d'un exemplaire non associé à un rayon
- Correction : la page de stocks n'affiche plus les exemplaires perdus lorsque
  le filtre "En stock" est sélectionné
- Correction d'un problème d'affichage de prix dans les pages de résultats
- Correction : un article numérique indisponible ne peut plus être ajouté au
  panier

## 2.18.0 (1er mai 2016)

- Il est désormais possible d'ajouter un article à un rayon directement depuis
  la page d'ajout ou de modification d'un exemplaire lié.
- Ajout d'une page pour lister et supprimer les articles d'un rayon
- Correction d'un bug affectant l'ajout d'un livre à un rayon

## 2.17.0 (15 avril 2016)

- Ajout d'un éditeur de thème intégré
- Ajout d'un lien vers la bibliothèque numérique dans les courriels de
  confirmation de commande et de paiement lorsque la commande contient des
  articles téléchargeables
- Amélioration de la page d'erreur s'affichant lors de l'accès à un page
  à laquelle l'utilisateur n'a pas le droit d'accéder
- Éditeur : correction d'un problème affectant l'interface d'administration
- Correction d'un bug affectant l'ajout d'un livre dans un panier depuis
  la page des résultats de recherche

## 2.16.0 (3 avril 2016)

- Ajout d'une option de site pour définir la monnaie utilisée
- Ajout d'une option de site pour définir le mode stock virtuel
- Ajout des options Format et Styles à la barre d'outils de mise en forme
- Correction d'un bug affectant la fenêtre d'ajout d'éditeur depuis la fiche
  article (#72)
- Correction d'un bug affectant l'affichage des billets de blog (#73)
- Correction d'un problème lors de l'ajout dans un panier VPC d'un livre dont
  un exemplaire est ajouté à une caisse magasin (#74)
- Correction d'un problème de cache lors de l'ajout d'un livre en caisse

## 2.15.3 (28 mars 2016)

- Correction d'un bug affectant la barre d'outils de mise en forme
- Optimisation du cache HTTP pour les fichiers javascript

## 2.15.2 (27 mars 2016)

- Correction d'un bug affectant la création d'un nouvel article

## 2.15.1 (26 mars 2016)

- Correction d'un problème d'affichage des exemplaires perdus par année

## 2.15.0 (25 mars 2016)

- Ajout de FLAC et RTF aux formats de fichiers téléchargeables supportés
- Ajout d'une option de site pour définir le stock virtuel minimum
- Ajout d'une option pour activer le protocole HTTPS pour Axys
- Ajout d'une page de statistiques pour les exemplaires perdus
- La jauge des campagnes de financement peut désormais dépasser 100 %
- Les contreparties mises en avant s'affichent désormais en tête de liste

## 2.14.1 (9 mars 2016)

- Correction : Le paiement par Payplug est désormais disponible aussi pour les
  commandes à retirer en magasin

## 2.14.0 (6 mars 2016)

- Mise du module de paiement aux nouvelles normes de sécurité Paypal
- Ajout d'une option de site permettant d'afficher une case à cocher "J'accepte
  les Conditions Générales de Vente"
- Ajout de raccourcis de navigation sur les pages de campagne de financement
  participatif

## 2.13.2 (3 mars 2016)

- Correction de l'apparition du message d'erreur "CFReward not found" lors de
  l'ajout de certains exemplaires.
- Correction : seuls les articles téléchargeables apparaissent désormais dans
  la bibliothèque numérique
- Correction des tags OpenGraph des billets de blog
- Correction du pluriel pour les contributeurs et les contreparties disponibles
  dans les campagnes de financement participatif

## 2.13.1 (26 février 2016)

- Le pays de l'utilisateur est correctement sélectionné dans le panier et sur la
  page de commande s'il est connu
- Amélioration de l'animation d'agrandissement des images
- Correction d'un problème de redirection après la modification d'une fiche
  article
- Correction d'un problème d'affichage des extraits promotionnels dans la
  bibliothèque numérique (#62)
- Correction d'un problème de validation des EAN lors de l'association d'un
  fichier téléchargeable à une fiche article (#61)
- Correction d'un problème affectant le changement du poids d'un exemplaire
  depuis la page Inventaire
- Correction d'un problème d'affichage lors de l'ajout d'un élément à un
  article de type lot
- Correction d'un problème affectant le téléchargement d'extraits gratuit

## 2.13.0 (22 février 2016)

- Ajout d'une animation lors de l'ajout d'un livre au panier
- Amélioration des performances de la page "Ma bibliothèque numérique"
- Amélioration de la visibilité des livres mis à jour dans la bibliothèque
- Amélioration de l'encryptage des mots de passe lors de la création d'un compte
  utilisateur depuis Biblys
- Réencryptage du mot de passe lors de l'authentification depuis un autre
  site via l'API
- Intégration de la nouvelle API Payplug
- Le paiement par Payplug ou Paypal n'est plus proposé si le montant de la
  commande est inférieur à 1,00 €

## 2.12.0 (19 février 2016)

- Ajout d'une option de site pour définir une page statique comme étant
  la page d'accueil.
- Ajout d'un message pour les administrateurs indiquant qu'une page statique
  ou un billet de blog est hors-ligne.
- Refonte de l'interface de gestion des administrateurs
- Correction d'un bug affectant la mise hors-ligne d'un billet de blog.

## 2.11.2 (17 février 2016)

- Correction d'un problème lors du retrait d'un livre du panier en cours depuis
  la caisse

## 2.11.1 (16 février 2016)

- Correction d'un problème lors de l'ajout d'un livre numérique au panier
- Correction d'un problème de génération d'url lors de la création

## 2.11.0 (15 février 2016)

- Ajout d'un message d'attente pendant la mise à jour de Biblys
- Éditeur : les exemplaires présents dans un panier sont retirés lorsque
  l'article correspond est marqué comme épuisé
- Correction d'un problème d'affichage des prix sur certaines fiches articles
- Correction d'un problème permettant de télécharger des livres numériques
  précommandés et encore non parus

## 2.10.0 (30 janvier 2016)

- Ajout d'un champ _image_ et d'une option _mis en avant_ aux contreparties de
  financement participatif
- Amélioration de la lisibilité de la page _Gestion des médias_
- Correction de plusieurs bugs de l'animation d'agrandissement des images

## 2.9.1 (23 janvier 2016)

- Correction d'un bug affectant la création d'une contrepartie en quantité
  limitée

## 2.9.0 (3 janvier 2016)

- Ajout d'une option de site pour changer le message précisant la date
  d'expédition au moment de l'enregistrement d'une commande
- Correction d'un bug affectant le formulaire de modification des exemplaires

## 2.8.0 (21 décembre 2015)

- L'inscription à la newsletter est désormais proposé lors de l'enregistrement
  d'une commande et lors du téléchargement d'un livre gratuit.
- Ajout d'une page d'explication lors du téléchargement d'un livre gratuit
- Ajout d'un outil d'export des abonnés à la newsletter
- Correction d'un bug obligeant à choisir une option d'expédition pour un panier
  ne contenant que des livres numériques
- Correction d'un bug affectant le téléchargement de fichiers numériques

## 2.7.0 (13 décembre 2015)

- Ajout d'un champ image aux campagnes de financement participatif
- Ajout de champs "Campagne liée" et "Contrepartie liée" à la fiche exemplaire
- Le mode d'expédition n'est plus sélectionné par défaut si plusieurs sont
  disponibles
- Ajout d'un message d'erreur si aucun mode d'expédition n'est sélectionné à la
  validation de la commande
- L'outil de mise à jour affiche désormais toutes les nouvelles versions
  disponibles plutôt qu'uniquement la dernière
- Amélioration des performances de l'outil de mise à jour

## 2.6.0 (7 décembre 2015)

- Les pages d'inscription et de désinscription à la newsletter sont désormais
  personalisables
- Il est désormais possible de se réabonner à une newsletter si on s'est
  désabonné par le passé

## 2.5.1 (2 décembre 2015)

- Les campagnes de financement participatif et les quantités disponibles pour
  chaque contrepartie sont désormais automatiquement mises à jour après chaque
  nouvelle commande.
- Ajout d'un lien pour actualiser manuellement les stats de campagne
- Ajout du nombre de jours restant avant la fin de la campagne
- Les contreparties sont désormais classées de la moins chère à la plus chère
- Le pourcentage de progression de la commande est désormais plus précis

## 2.5.0 (30 novembre 2015)

- Refonte des pages publiques de campagnes de financement participatif
- Ajout de boutons de partage sociaux et de balises OpenGraph sur les pages
  de campagnes de financement participatif
- Correction d'un bug affectant les nouveaux champs disponibilité sur les
  pages de collection

## 2.4.1 (23 novembre 2015)

- Correction d'un bug affectant la réception des notifications Paypal
- Correction de diverses failles de sécurité

## 2.4.0 (22 novembre 2015)

- Ajout d'outils pour créer et gérer une campagne de financement participatif
- Diverses optimisations pour le référencement
- Gestion du statut à paraître dans les templates articles
- Biblys adopte la gestion sémantique de version à partir de la version 2.4.0

## 2.3.25 (16 novembre 2015)

- Ajouter d'un bouton pour actualiser les paniers dans l'administration
- Diverses optimisations pour le référencement
- Correction d'un nouveau bug affectant le champ _Disponibilité_ des articles

## 2.3.24.3 (9 novembre 2015)

- Correction d'un bug affectant le champ _Disponibilité_ des articles

## 2.3.24.2 (26 octobre 2015)

- Correction d'un bug qui permettait d'acheter des livres numériques sans être
  identifié

## 2.3.24.1 (20 octobre 2015)

- Correction d'un bug affectant le champ prix des fiches articles
- Correction d'un bug affectant l'association d'un article à un fichier
  téléchargeable ajouté par FTP

## 2.3.24 (18 octobre 2015)

- Biblys utilise désormais les codes Dilicom pour la disponibilité des articles
- Correction du bouton de validation de commande si le panier ne contient qu'un
  livre numérique

## 2.3.23 (9 octobre 2015)

- Panier : Refonte des frais de port pour permettre aux éditeurs de proposer
  plusieurs tarifs, notamment le retrait en magasin
- Panier : Sélection automatique du premier tarif postal proposé lors du choix
  du pays
- Panier : Le pays choisi par l'utilisateur est sauvegardé et proposé
  automatiquement à la commande suivante
- Nouvelle animation de zoom au clic sur une couverture
- Ajout d'un journal des transactions Paypal
- Correction d'un bug qui laissait apparaître sur la page d'accueil des billets
  hors-ligne ou à paraître
- Correction d'un problème d'affichage des titres contenant des guillemets
- Corection d'un bug qui empêchait d'ajouter plusieurs fois le même livre à
  un panier

## 2.3.22 (2 octobre 2015)

- Librairies: réactivation de la précommande pour les livres à paraître s'ils
  sont en stock

## 2.3.21 (27 septembre 2015)

- Refonte de l'outil de gestion des rayons
- Correction d'un bug de l'antispam du formulaire de la page contact

## 2.3.20.1 (21 septembre 2015)

- Correction d'un bug lors de l'ajout d'un article à un rayon
- Correction d'un bug lors de l'ajout d'un fichier téléchargeable

## 2.3.20 (21 septembre 2015)

- Ajout d'un modèle HTML personnalisable pour les rayons
- Ajout d'options de tri et de filtres par date de parution pour les rayons
- Correction d'une faille de sécurité XSS dans les résultats de recherche
- Correction d'un bug qui affichait un livre disponible en occasion à 0,00 €
  au lieu d'indisponible dans les sites

## 2.3.19.1 (16 septembre 2015)

- Correction d'un bug lors de l'ajout au panier d'un livre numérique par un
  utilisateur non identifié
- Correction d'un bug lors de la création d'un paiment Paypal avec port
- Amélioration des performances de l'affichage des résultats de recherche

## 2.3.19 (13 septembre 2015)

- Ajout d'une page d'accueil configurable
- Intégration de la nouvelle interface de paiement Paypal
- Ajout d'une option pour demander la suppression d'un article
- Les articles à paraître ne peuvent plus être ajoutés au panier (sauf
  précommande).

## 2.3.18.5 (6 septembre 2015)

- Correction du bug d'authentification lors du téléchargement d'un fichier

## 2.3.18.4 (30 août 2015)

- Vérification de l'adresse e-mail avant l'ajout à la newsletter

## 2.3.18.3 (29 août 2015)

- Correction de plusieurs bugs et failles de sécurité

## 2.3.18.2 (1er août 2015)

- Correction d'un problème d'affichage de la fiche d'identité éditeur
- Correction d'un problème lié au référencement par les moteurs de recheche

## 2.3.18.1 (25 juillet 2015)

- Correction d'un problème de modification des options du site
- Correction des champs description courte et spécialités sur la fiche éditeur

## 2.3.18 (24 juillet 2015)

- Affichage du mode de paiement dans la liste des commandes
- Correction d'un problème d'affichage des options du site
- Correction d'un problème lors de l'association de deux articles

## 2.3.17 (8 juillet 2015)

- Ajout d'un mécanisme de mise à jour de Biblys dans l'administration
- Ajout d'une option pour définir le stock actif d'un libraire

## 2.3.16.1 (7 juillet 2015)

- Amélioration de l'algorythme de cryptage des mots de passe

## 2.3.16 (5 juillet 2015)

- Choix du mode de paiement lorsqu'une commande est marquée comme payée
- Recherche libre dans les commandes (nom du client, numéro de commande, etc.)
- Les commandes disparaissent après une seconde si elles ne correspondent plus
  au filtre en cours
- Ajout d'un champ pour associer un client à une commande après coup
- Ajout d'un rapport téléchargeable type immatériel pour les ventes numériques
- Affichage d'un lien sur les pages auteurs vers les billets associés
- Corrections diverses

## 2.3.15 (28 juin 2015)

- Le formulaire de contact peut désormais être protégé contre le spam par
  ReCaptcha

## 2.3.14 (16 juin 2015)

- Refonte de la page de gestion des commandes web
- Les boutons de gestion d'une commande sont toujours visibles et plus dans un
  menu déroulant
- La liste est mise à jour lorsqu'une commande change de statut (expédiée,
  payée, etc.)
- Les commandes sont automatiquement marquées comme expédiées quand elles sont
  payées si elles ne contiennent que des articles immatériels (livres
  numériques, abonnements, etc.)

## 2.3.13 (12 juin 2015)

- Restructuration, optimisations et corrections diverses

## 2.3.12 (7 juin 2015)

- Support : affichage de la position du ticket dans la liste d'attente
- Affichage d'un message dans l'administration après une mise à jour de Biblys
- Correction du problème d'affichage du champ Bonus pour les éditeurs

## 2.3.11 (4 juin 2015)

- Ajout d'un outil pour ajouter une collection entière à un rayon

## 2.3.10 (15 mai 2015)

- Ajout d'un fichier de configuration au format YAML

## 2.3.9 (7 mai 2015)

- Ajout d'une API d'authentification pour les services externes, notamment le
  forum du Bélial'

## 2.3.8 (2 mai 2015)

- Ajout d'un outil d'inventaire pour les librairies
- L'ancienne page Inventaire a été renommée Stock
- Ajout de l'emplacement de stockage sur la page Stock

## 2.3.7 (24 avril 2015)

- Amélioration de la présentation des tableaux à l'impression

## 2.3.6 (16 avril 2015)

- Meilleure gestion des erreurs lors de l'import nooSFere

## 2.3.5 (4 avril 2015)

- Ajout d'options de configuration de site

## 2.3.4 (3 avril 2015)

- Ajout d'une option d'envoi des emails depuis un serveur SMTP distant

## 2.3.3 (28 mars 2015)

- Il n'est plus possible de modifier les articles d'un éditeur Biblys depuis un
  autre site
- (Correction) L'annulation de commande fonctionne à nouveau
- (Correction) La vérification du prix article/exemplaire avant ajout au panier
  est désactivé sur les sites libraires

## 2.3.2 (12 mars 2015)

- Meilleure présentation des tickets de support
- Les tickets de support peuvent être marqués comme résolus avant d'être clos
- Ajout d'un filtre dépôt dans l'inventaire
- Ajout d'un filtre par état pour le chiffre d'affaires
- Ajout d'un onglet dépôt pour l'état du stock

## 2.3.1 (3 mars 2015)

- Ajout d'un outil de support technique intégré à Biblys

Biblys 2.3 : Financement participatif

- Nouvel outil de financement participatif

## 2.2.20 (16 février 2015)

- Liste d'exemplaires : Ajout d'un bouton pour regrouper les exemplaires par
  article
- Évènements : Ajout d'un module pour associer des images à un évènement
- (Correction) Le bouton de masquage des articles fonctionne à nouveau

## 2.2.19 (5 février 2015)

- Liste d'envies : il est possible de changer le nom et la visibilité (
  publique/privée) d'une liste

## 2.2.18 (1er février 2015)

- Traitement par lot : application d'une promotion sur tous les exemplaires
  d'une liste
- Page d'inventaire : Ajout d'un filtre par type d'article
- Il est désormais possible de lier un billet de blog à un éditeur

## 2.2.17 (30 janvier 2015)

- Ajout d'une case à cocher "Dépôt" sur les pages de stock
- (Correction) Affichage d'un message d'erreur lors de la création d'un
  contributeur déjà existant depuis la fiche article
- (Correction) Inversion du montant de la TVA et du CA HT sur la page Chiffre
  d'affaires

## 2.2.16 (26 janvier 2015)

- Calcul de la TVA au moment de l'achat d'un article
- Ajout d'une liste des listes sur la page inventaire
- Ajout d'un menu déroulant pour sélectionner un pays sur le formulaire de
  modification des commandes
- (Correction) Affichage des factures si le pays du client n'est pas spécifié

## 2.2.15 (16 janvier 2015)

- Outil d'édition des exemplaires en lot à partir d'une liste
- Ajout des exemplaires d'une page d'inventaire à une liste
- Nouveau type d'article : périodique (TVA 2,1% en France)
- (Correction) Affichage par défaut de l'inventaire
- (Correction) Affichage des fichiers téléchargeables publics (extraits)

## 2.2.14 (12 janvier 2015)

- Inventaire : ajout de filtres par éditeur et par emplacement
- Inventaire : affichage de l'email ou du numéro de client si le nom est inconnu

## 2.2.13 (9 janvier 2015)

- (Correction) Les articles vendables sur commande sont bien marqués comme tels
- (Correction) Menu de selection d'un fournisseur
- (Correction) Case à cocher fournisseur "Vendable sur commande"

## 2.2.12 (8 janvier 2015)

- Il est désormais possible de supprimer un fournisseur

## 2.2.11 (6 janvier 2015)

- (Correction) Il est à nouveau possible d'ajouter plusieurs articles au panier

## 2.2.10 (2 janvier 2015)

- Refonte du système de calcul des frais de port
- Mise à jour des tarifs postaux
