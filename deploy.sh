#!/bin/sh
path=$PWD/${0%/*};

cd $path;
git pull;

git submodule init;
git submodule update;
git submodule foreach --recursive git submodule init
git submodule foreach --recursive git submodule update
git submodule foreach --recursive git checkout master
git submodule foreach --recursive git pull

php composer.phar install
php composer.phar update

php app/console doctrine:schema:update --force 
php app/console assets:install --symlink web
php app/console cache:clear --env=prod

chown -R apache:apache ../www

echo 'it works'
