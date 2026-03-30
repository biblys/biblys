# ORM Legacy

## Contexte historique

Autrefois, les requêtes en base de données étaient écrite directement en SQL
avec la méthode `mysql` puis `mysqli`.

Par la suite, un ORM maison naïf a été créé dans le répertoire `inc` avec des
modèles appelées "Entités" (classes héritant de `Entity`) et des repositories (
classes héritant de `EntityManager`).

Enfin, cet ORM maison a été remplacé par Propel dont les modèles et repositories
sont dans le dossier `src/Model`. Il reste aussi des requêtes SQL en dur
executée grâce à la méthode statique `prepareAndExecute` qui prévient les
injections SQL.