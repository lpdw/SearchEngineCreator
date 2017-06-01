SearchEngineCreator
========================

Pré-requis :
--------------
Git

Composer

Etape :
--------------
Récupérer l’application:
> git clone https://github.com/lpdw/SearchEngineCreator.git
> cd SearchEngineCreator

Installer & mettre à jours les dépendance
> Composer install

Définir les paramètre de votre base de donnée

Créer la base de donnée
>php bin/console doctrine:schema:update --force

Mettre a jours les assets
>php bin/console assets:install
