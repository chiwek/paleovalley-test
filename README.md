## Laravel test app
A simple Products CRUD REST API run on laravel sail

## To run the code and tests, here are the steps:

1. Pull the repo
2. run composer install
3. fire up laravel sail, execute: `php artisan sail:install && sail up -d`      ----- (in case of confusion, you can find the docs here https://laravel.com/docs/10.x/sail)
4. migrate the db, execute: `sail artisan migrate`
5. run tests: `sail artisan test`, make sure all are green. sqlite is used for DB in testing just to get much better speeds in local env on test execution. You can switch this to MySQL easily in phpunit.xml if needed
6. use sanctum-token postman collection requests to create your auth token. You can find collections in storage/postman-collections.
7. call endpoints from products postman collection to check their functionality
