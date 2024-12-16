test:
	./vendor/bin/phpunit

test-file:
	./vendor/bin/phpunit $(file)

serve:
	php artisan serve

migrate:
	php artisan migrate

seed:
	php artisan db:seed

cache-clear:
	php artisan cache:clear

config-clear:
	php artisan config:clear

route-clear:
	php artisan route:clear

view-clear:
	php artisan view:clear