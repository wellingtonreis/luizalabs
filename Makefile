start-job:
	php artisan queue:work --queue=rabbitmq --once

test:
	./vendor/bin/phpunit

test-file:
	./vendor/bin/phpunit --testdox $(file)

phpstan:
	./vendor/bin/phpstan analyse --memory-limit=1G
	
serve:
	php artisan serve

migrate:
	php artisan migrate

seed:
	php artisan db:seed

migrations:
	php artisan migrate && php artisan db:seed

cache-clear:
	php artisan cache:clear

config-clear:
	php artisan config:clear

route-clear:
	php artisan route:clear

view-clear:
	php artisan view:clear

view-logs:
	tail -f storage/logs/laravel.log

logs-clear:
	truncate -s 0 storage/logs/laravel.log
