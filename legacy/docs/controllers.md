# Controllers legacy

## Contexte historique

Les controllers étaient autrefois dans `controllers/common`.
Depuis quelques années, ils sont petit à petit modernisés et transférés dans `src/`.
Il reste des controllers legacy à refactoriser et déplacer.

## Controller legacy

Les controllers legacy sont dans `controllers/common` avec :

- dans `php`, les controllers appelés directement par le navigateur et qui
  retournent du HTML
- dans `xhr`, les controllers appelés par le code javascript (XHR ou fetch) et
  qui retournent du JSON
- dans `feeds`, les controllers utilisés pour générer des flux RSS (a priori
  plus utilisé) et qui retourne du XML
  C'est une idée générale, mais qui n'est pas forcément respectée (certains
  controllers dans `php` peuvent retourner du JSON et certains controllers dans
  `xml` peuvent renvoyer du HTML)

Ce sont de simples fichiers PHP qui lisent directement la base de données qui
écrivent leur réponse en ajoutant une chaine de caractères à la variable globale
`$_ECHO` laquelle était ensuite lue et affichée par le framework.

Certains controllers legacy sont dans un état intermédiaire : ils exportent une
fonction anonyme qui retourne un object `Response` et prennent en paramètre des
services injectables.

## Controllers modernes

Les controllers modernes utilisent certaines libraries du Framework `Symfony` et en reprennent globalement les patterns.

Ils sont dans deux répertoires :

- dans `AppBundle`, les controllers appelés directement par le navigateur et qui
  retournent du HTML
- dans `ApiBundle`, les controllers appelés par le code javascript (XHR ou fetch) et
  qui retournent du JSON

Idéalement, ils ne devraient jamais 
- Utiliser uniquement l'ORM Propel et jamais l'ORM legacy (classes dans `inc` héritant de `inc.Entity.class.php`)
- Utiliser uniquement l'injection de services et pas instancier de services directement