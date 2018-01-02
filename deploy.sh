#!/usr/bin/env bash
git pull --rebase
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug --no-warmup
php bin/console cache:warmup --env=prod
php bin/console doctrine:migrations:migrate
