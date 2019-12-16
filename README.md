# Welcome to Pre Order Api

This API allows you to add products to the basket and pre-order. This api created with Symfony 4.4

# Installation

 - Clone project
 ``````
 git clone git@github.com:emrullahshn/pre-order-api.git
 ``````
 - Run composer install
 ````
 composer install 
 ````
 - Set up database connection config in .env
 - Create database
 ````
 bin/console doctrine:database:create
````
 - Migrate migrations
 ````
 bin/console doctrine:migrations:migrate
 ````
 - Load fixtures
 ````
 bin/console doctrine:fixtures:load
 ````
 
# API Documentation
You can access API's documentation at api/doc or api/doc.json path.
Used nelmio api doc for documentation. 

# Postman Collection
```
https://www.getpostman.com/collections/80951e2569529cbbe63f
```

# TODO
 - Project to do dockerize
 - Money class will be used for price calculates
 - Mocky.io will be used for test Rest API
