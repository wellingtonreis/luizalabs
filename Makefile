start-app:
	docker exec -it laravel-app /bin/bash -c "php artisan migrate && php artisan db:seed && exit"

start-job:
	docker exec -it laravel-app /bin/bash -c "php artisan queue:work --queue=rabbitmq --once && exit"

generante-transfers:
	docker exec -it laravel-app /bin/bash -c "php artisan db:seed && exit"

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

view-logs:
	docker exec -it laravel-app /bin/bash -c "tail -f storage/logs/laravel.log"

logs-clear:
	docker exec -it laravel-app /bin/bash -c "truncate -s 0 storage/logs/laravel.log"
