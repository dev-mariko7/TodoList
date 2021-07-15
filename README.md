#TodoList

Symfony project TodoList (PHP-SYMFONY)

## How to install the project

### Requirements

- Symfony CLI

### Installation and configuration

Please clone the project

####create and edit .env.local file

- create `.env.local` file to root project
- add the line `DATABASE_URL` and complete it following the [Doctrine rules](https://symfony.com/doc/current/doctrine.html)

Run the following commands:

>- `symfony composer install`
>- `php bin/console doctrine:database:create`
>- `php bin/console doctrine:migrations:migrate`

## Development process

Add data fixtures
>- `php bin/console doctrine:fixtures:load`

Load the project
>- `php -S 127.0.0.1:8000 -t public`

## How to run unit tests and functionnals testing

Load unit tests
- runt the following command : `php bin/phpunit`
