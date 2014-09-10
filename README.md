## Installation

Copy the parameters file (and update its content):

    cp app/config/parameters.yml.dist app/config/parameters.yml

Install the dependencies with [composer](http://getcomposer.org/) (assuming
you have composer available globally and named `composer`):

    composer install

Create your database (through PhpMyAdmin). Take care to use `utf8_general_ci`
default collation of the database. MySQL tends to use Latin1 by default.
Then create the tables in the database:

    php app/console doctrine:migrations:migrate

For the prod environment, you also need to dump the assetic files and warmup the cache:

    php app/console --env=prod --no-debug assetic:dump
    php app/console --env=prod --no-debug cache:clear --no-warmup
    php app/console --env=prod --no-debug cache:warmup

## Running the testsuite

### PHP

The tests are written using [PHPUnit](http://phpunit.de) so you need to install
PHPUnit 3.6. Then, you can run the tests by running `phpunit` at the root
of the project.

### Javascript

The tests are written using [Jasmine](http://pivotal.github.com/jasmine/)

You can run them simply by accessing the ``/tests`` url in your dev environment
(for instance ``http://localhost/AchieveMonkey/web/app_dev.php/tests``)