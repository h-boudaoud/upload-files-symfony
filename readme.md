## Upload file with Symfony
#### Symfony 5.0.8
Ce code sert uniquement à justifier mes connaissances du développements d'application avec Symfony.

This code is only used to justify my knowledge of application development with Symfony.


## Installation
```
# We clone the deposit!             <-->  On clone le dépot!
git clone https://github.com/h-boudaoud/my-first-app-Symfony.git

# We move in the folder             <-->  On se déplace dans le dossier
cd myFirstAppSymfony

# We install the dependencies!      <-->  On installe les dépendances !
composer install

#  We create the database           <-->  On créé la base de données
php bin/console doctrine:database:create

# We perform the migrations         <-->  On exécute les migrations
php bin/console doctrine:migrations:migrate

#  We execute the fixture           <-->  On exécute la fixture
php bin/console doctrine:fixtures:load --no-interaction

#  We launch the server             <-->  On lance le serveur 
#(exe: run-server.sh)
run-server.sh
```

##  Console commands used to create this project 
#### Install App  and components
```
 composer create-project symfony/skeleton uplode-files-symfony
 composer require maker twig validator annotations orm form && composer require profiler --dev

```
#### Add Form UploadFilesType
 Add class UploadArrayFiles and Build the form UploadFilesType
```
php bin/console make:form UploadFilesType UploadArrayFiles

```
 Change the field types reference and/or add fields properties  to the form
 
 #### Add controller UploadFiles
  Add class UploadArrayFiles and Build the form UploadFilesType
 ```
 php bin/console make:controller UploadFiles
 
 ```
  Alter the controller and the template to upload the files to the database or to a folder

 

 ![alt text](https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/OOjs_UI_icon_alert_destructive_black-darkred.svg/44px-OOjs_UI_icon_alert_destructive_black-darkred.svg.png "Warning")
 Any type of file can be downloaded
 
  #### Add Entities, controllers, forms
  
 ```
#Entity Product, Image,  Category ...
php bin/console make:entity Product

#Controller ProductController, ImageConroller, ...
php bin/console make:controller Product

#form ProductType (form for Product entity), .... 
php bin/console make:form Product Product

 ```
Alter Entities, controllers, forms and templates

#### Configuring the Database
customize environment variable called DATABASE_URL inside .env
```
# create Database
php bin/console doctrine:database:create

# Migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate

```
 #### Create random fixtures  Faker
 
```
 # to load a "fake" dataset into a database
 composer require --dev doctrine/doctrine-fixtures-bundle
 
 # load  random Faker devices
 composer req --dev fzaninotto/faker
 
```
All the types of data that Faker can generate at https://github.com/fzaninotto/Faker#formatters


## Author
h.boudaoud



