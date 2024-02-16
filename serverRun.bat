@echo on
@php bin\console doctrine:database:create --if-not-exists 2>NUL
php bin\console doctrine:schema:drop --full-database --force
php bin\console doctrine:schema:create
php bin\console doctrine:fixtures:load --append
php -S 0.0.0.0:8000 -t public 
