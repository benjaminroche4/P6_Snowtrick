<h1>Projet n°6 - Snowtricks</h1>

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2ef9698e4a054e7f9c8c9c4182b71d8a)](https://www.codacy.com/gh/benjaminroche4/P6_Snowtrick/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=benjaminroche4/P6_Snowtrick&amp;utm_campaign=Badge_Grade)

<hr>
<p>Création d'un site communautaire  de partage de figures de snowboard à l'aide du framework Symfony.</p>

<h2>Instalation</h2>
<hr>

1. Clonez ou téléchargez le repository GitHub dans le dossier voulu :
   ```
   git clone https://github.com/benjaminroche4/P6_Snowtrick.git
   ```
2. Passez en mode "dev" dans le fichier ".env" :
   ```
   APP_ENV=dev
   ```
3. Configurez les variables d'environnement tels que la connexion à la base de données et adresse mail dans le même fichier :
   ```
   DATABASE_URL="mysql://root:root@127.0.0.1:8889/snowtricks?serverVersion=13&charset=utf8"
   MAILER_DSN=sendmail://default
   ```
4. Téléchargez les dépendances nécessaires grace à composer :
   ```
   composer install
   ```
5. Installez la base de données à l'aide des commandes suivantes dans votre terminal : 
   ```
   php bin/console doctrine:database:create
   php bin/console doctrine:schema:update --force
   php bin/console doctrine:fixtures:load
   ```
6. Lancez le serveur à l'aide du terminal avec la commande suivante : 
   ```
   symfony server:start
   ```
7. Le projet est maintenant installé. Vous pouvez tester l'application à cette adresse :
   ```
   http://127.0.0.1:8000
   ```
