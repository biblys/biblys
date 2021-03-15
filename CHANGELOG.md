# Changelog

## DEV

Améliorations

- Ajout d'un champ pour ajouter un logo à une fiche éditeur
- Le bouton "Vider tous les paniers" a été supprimé
- Les redirections de pages causées par un changement d'url utilisent désormais
  le statut HTTP 301 plutôt que 302

Corrections

- Le statut HTTP des pages d'erreur 404 est bien 404

### 2.51.3 (5 mars 2021)

Correction : le raccourci "Ajouter au stock" de la barre d'administration
fonctionne à nouveau.

### 2.51.2 (26 février 2021)

Corrections

- L'accès au tableau de bord en tant qu'éditeur fonctionne à nouveau
- Il n'est plus possible d'associer un article à un cycle supprimé

### 2.51.1 (18 février 2020)

Corrections

- Les erreurs serveur survenant lors de l'import d'un article s'affiche à
  nouveau correctement
- L'import d'un article associé à un contributeur ayant précédemment été
  supprimé fonctionne à nouveau

### 2.51.0 (5 février 2021)

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

### 2.50.3 (26 janvier 2021)

- Correction : cliquer sur annuler dans la boîte de dialogue du numéro de suivi
  d'une commande ne marque plus la commande comme expédiée.

### 2.50.2 (15 décembre 2020)

Corrections

- La création ou la modification d'une tranche de frais de port avec un montant
  de 0,00 € fonctionne à nouveau.
- L'outil de gestion des frais de port affiche désormais correctement les
  tranches sans commentaires.

### 2.50.1 (14 octobre 2020)

Corrections

- L'échec d'un paiement via PayPlug ne cause plus d'erreur
- La modification des options "Exempté de TVA" et "Livres vendables sur
  commande" des fournisseurs fonctionne à nouveau

### 2.50.0 (7 octobre 2020)

Améliorations

- Ajout de deux options `shipped_mail_subject` et `shipped_mail_message`
  permettant de personnaliser le courriel de confirmation d'expédition d'une
  commande
- Le sujet du courriel de confirmation d'expédition d'une commande à retirer en
  magasin est désormais "Commande disponible en magasin" plutôt que "Commande
  expédiée"
- Ajout d'une page pour gérer les comptes autorisés à gérer un éditeur

### 2.49.4 (30 septembre 2020)

Corrections

- L'écriture des journaux d'erreurs fonctionne à nouveau pour l'authentification
  les paiements par Paypal

### 2.49.3 (23 septembre 2020)

Correction d'un problème qui pouvait conduire, dans certains cas, à l'apparition
des rayons associés à un article depuis un autre site à apparaître sur les
fiches articles.

### 2.49.2 (15 septembre 2020)

Corrections

- L'import d'un article associé à une collection déjà existante fonctionne à
  nouveau
- Le retrait d'un rayon associé à un article fonctionne à nouveau
- L'import d'un article dont le champ auteurs dépasse 256 caractères ne provoque
  plus d'erreur
- La mise à jour d'un contributeur existant ne provoque plus d'erreur
- Le tronquage d'une chaîne de caractères utf-8 est calculé au nombre de bits et
  plus au nombre de caractères

### 2.49.1 (7 septembre 2020)

Corrections

- La case à cocher "À la une" du formulaire d'édition d'un billet peut à nouveau
  être décochée.
- Le numéro de version d'une image de couverture est correctement inclus dans
  les urls du CDN WeServ
- L'ajout d'une valeur dépassant 256 caractères dans le champ auteurs ne
  provoque plus d'erreur

### 2.49.0 (2 septembre 2020)

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

### 2.48.1 (10 juillet 2020)

Corrections

- Les livres indisponibles n'apparaissent plus comme disponibles dans la liste
  des alertes s'il existe un exemplaire supprimé
- Les contributeurs supprimés n'apparaissent plus sur certaines fiches livres
- La suppression d'un compte administateur fonctionne à nouveau
- La suppression d'un panier fonctionne à nouveau

### 2.48.0 (5 juin 2020)

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

- La case d'abonnement à la newsletter lors du téléchargement d'un livre numérique
  gratuit n'est plus cochée par défaut
- La suppression de tous les panier fonctionne à nouveau
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

### 2.47.1 (4 mars 2020)

- Les frais de port globaux fonctionnent à nouveau

### 2.47.0 (1er mars 2020)

Améliorations

- Refonte de la page de gestion des frais de port
- Ajout un lien vers Matomo dans l'administration
- Ajout d'un filtre "Sans poids" à la page des stocks
- Ajout des rayons au suivi e-commerce de Matomo
- Ajout d'une option de suppression d'un éditeur
- Les alertes sont désormais supprimées lorsque l'article correspondant est
  acheté (#145)
- Les pictogrammes de disponibilité d'un exemplaire sont désormais plus lisibles

Corrections

- Le panier propose uniquement le mode d'expédition le moins cher pour chaque
  type d'envoi
- La suppression du champ item d'un article fonctionne à nopuveau
- L'association d'un article à un autre via le chant "Lier à l'article"
  fonctionne à nouveau
- Les actions supprimer et retourner un exemplaire fonctionnent à nouveau

### 2.46.0 (2 février 2020)

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

### 2.45.0 (1er décembre 2019)

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

### 2.44.0 (5 octobre 2019)

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
  s'affiche désormais en jours, heures, minutes, puis secondes
- Les campagnes de financement participatif commencent à minuit et se terminent
  à 23h59

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
  aux problèmes de mise en cache
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
  les exemplaires qui ne sont pas déjà dans le panier d'un autre client
- Correction d'un problème affectant l'éditeur de thème sur d'anciennes versions de
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
  article offert pour un nombre d'article achetés au sein d'une collection
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
- Correction d'un bug laissant apparaître des articles supprimées lors de
  l'ajout rapide d'exemplaires

## 2.36.0 (25 mai 2018)

- Ajout d'une option pour définir un message à ajouter aux courriels des alertes
- Ajout d'une option pour définir le repertoire de stockage des images
- Amélioration de l'outil de génération des termes de recherches lorsqu'il est
  utilisé avec un grand nombre de titres
- Amélioration l'importation nooSFere pour des livres référencés avec un ISBN-10
- Les pied de pages des factures ne sont plus répétés à chaque page lors de
  l'impression
- Ajout d'un message d'avertissement sur la caisse pour les assujetis à la TVA
- Ajout d'un message d'avertissement RGPD sur l'outil de newsletter
- Correction d'un bug affectant le calcul du nombre d'articles dont les termes
  de recherches doivent êtres regénérés
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
- Correction d'un bug laissant apparaître des articles supprimés lorsqu'ils étaient liés
  aux articles
- Correction d'un bug lors de l'accès à une fiche article depuis le formulaire de
  modification
- Correction un bug affectant l'ajout au panier sur Internet Explorer

## 2.34.1 (17 février 2018)

- Correction d'un bug affectant la redirection après création ou modification
  d'un article

## 2.34.0 (27 janvier 2018)

- Ajout d'une page d'administration listant tous les articles avec une option d'export
  au format CSV
- Correction d'un bug affectant la création d'adresse unique pour les articles
- Correction d'un bug affectant l'affichage d'un message d'erreur lors de l'ajout d'un
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
- Ajout d'une option de site "collection_filter_hide" pour masquer des collections
- Le panier listent désormais les livres triés par date d'ajout (#121)
- Les mots-clés s'affichent désormais par ordre alphabetique sur les fiches articles (#129)

## 2.31.1 (10 octobre 2017)

- Correction d'un bug qui permettait de créer des mots-clés vide
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
- Correction d'un bug affectant l'ajout de mot-clés sur une fiche article vierge
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
- Tous les type de contributeurs d'un article sont désormais ajoutés à ses
  termes de recherche (#115)
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
- Information des suivi de conversions sur la page de la commande
- Ajout de la possibilité de supprimer un article
- Ajout d'un modèle 'Page d'accueil (derniers articles parus)' à l'éditeur
  de thèmes
- Ajout d'un modèle 'Formulaire de contact' à l'éditeur de thèmes
- Affichage d'une erreur si un fichier media présent sur le disque ne possède
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
- Correction : l'exemplaire disparaît après suppression depuis la page des stocks

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
  la page d'ajout ou de modification d'un exemplaire lié
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
- Les contreparties mise en avant s'affichent désormais en tête de liste

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
  d'une commande et lors du téléchargement d'un livre gratuit
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
  nouvelle commande
- Ajout d'un lien pour actualiser manuellement les stats de campagne
- Ajout du nombre de jour restant avant la fin de la campagne
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
- Les articles à paraître ne peuvent plus être ajoutés au panier (sauf précommande)

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
- Correction des champs description courte et spécialités dans la fiche éditeur

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
- Les commandes disparaissent après une seconde si elles ne correspondent plus au filtre en cours
- Ajout d'un champ pour associer un client à une commande après coup
- Ajout d'un rapport téléchargeable type immatériel pour les ventes numériques
- Affichage d'un lien sur les pages auteurs vers les billets associés
- Corrections diverses

## 2.3.15 (28 juin 2015)

- Le formulaire de contact peut désormais être protégé contre le spam par ReCaptcha

## 2.3.14 (16 juin 2015)

- Refonte de la page de gestion des commandes web
- Les boutons de gestion d'une commande sont toujours visibles et plus dans un menu déroulant
- La liste est mise à jour lorsqu'une commande change de statut (expédiée, payée, etc.)
- Les commandes sont automatiquement marquées comme expédiées quand elles sont payées si elles ne contiennent que des articles immatériels (livres numériques, abonnements, etc.)

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

- Ajout d'une API d'authentification pour les services externes, notamment le forum du Bélial'

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

- Il n'est plus possible de modifier les articles d'un éditeur Biblys depuis un autre site
- (Correction) L'annulation de commande fonctionne à nouveau
- (Correction) La vérification du prix article/exemplaire avant ajout au panier est désactivé sur les sites libraires

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

- Liste d'exemplaires : Ajout d'un bouton pour regrouper les exemplaires par article
- Évènements : Ajout d'un module pour associer des images à un évènement
- (Correction) Le bouton de masquage des articles fonctionne à nouveau

## 2.2.19 (5 février 2015)

- Liste d'envies : il est possible de changer le nom et la visibilité (publique/privée) d'une liste

## 2.2.18 (1er février 2015)

- Traitement par lot : application d'une promotion sur tous les exemplaires d'une liste
- Page d'inventaire : Ajout d'un filtre par type d'article
- Il est désormais possible de lier un billet de blog à un éditeur

## 2.2.17 (30 janvier 2015)

- Ajout d'une case à cocher "Dépôt" sur les pages de stock
- (Correction) Affichage d'un message d'erreur lors de la création d'un contributeur déjà existant depuis la fiche article
- (Correction) Inversion du montant de la TVA et du CA HT sur la page Chiffre d'affaires

## 2.2.16 (26 janvier 2015)

- Calcul de la TVA au moment de l'achat d'un article
- Ajout d'une liste des liste sur la page inventaire
- Ajout d'un menu déroulant pour sélectionner un pays sur le formulaire de modification des commandes
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

- (Correction) Les articles vendable sur commande sont bien marqués comme tels
- (Correction) Menu de selection d'un fournisseur
- (Correction) Case à cocher fournisseur "Vendable sur commande"

## 2.2.12 (8 janvier 2015)

- Il est désormais possible de supprimer un fournisseur

## 2.2.11 (6 janvier 2015)

- (Correction) Il est à nouveau possible d'ajouter plusieurs articles au panier

## 2.2.10 (2 janvier 2015)

- Refonte du système de calcul des frais de port
- Mise à jour des tarifs postaux
