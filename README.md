
## Adresse publique du serveur

https://backoffice.zelazna.fr/login

### Description du back-office

Gestion des articles grâce a un **CRUD**. 
Et un apercu des articles et de leur contenu.

### Architecture

Model, view, controller (**MVC**) qui est un pattern récurrent en entreprise, et dans beaucoup de frameworks modernes 
et qui permet une bonne structure maintenable. 
Cela consiste en un découpage de la logique entre les différentes types de classes .

### Les routes API

* Pour un article en particulier :
https://backoffice.zelazna.fr/api/articles/{id}

* Pour tout les articles :
https://backoffice.zelazna.fr/api/articles

### Choix techniques

- **Slim 4** : Un micro-framework de php qui gère le routing.

- **Php DI** : Une librairies qui couplée avec Slim permet d'utiliser des classes dans d'autres de manière automatisée avec l'injection de dépendances.
       
- **Twig** : Un langague de templating pour le html en php permet de faire plus simplement et avec plus d'options la composante HTML.

- **Dokku** : Solution gratuite inspirée d'Heroku pour l'hébergement de l'application.
