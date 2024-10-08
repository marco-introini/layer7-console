.DEFAULT_GOAL := check

check:
	./vendor/bin/phpstan analyse app
	./vendor/bin/pint --test

test:
	./vendor/bin/pest

clear_all: clear
	rm -f .idea/httpRequests/*
	rm -f storage/app/livewire-tmp/*
	rm -fr public/media
	rm -f ./storage/logs/laravel.log

clear:
	php artisan route:clear
	php artisan config:clear
	php artisan view:clear

update:
	@echo "Running php version:"
	@php --version
	@echo "Current Laravel Version"
	php artisan --version
	@echo "\nUpdating..."
	composer update
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	php artisan filament:upgrade
	@echo "UPDATED Laravel Version"
	php artisan --version
	npm update

backup:
	php artisan backup:run

recreate: clear_all
	php artisan migrate:fresh --seed

recreate_with_fake_data: recreate
	php artisan db:seed --class FakeDataSeeder

recreate_with_real_data: recreate
	php artisan db:seed --class RealDataSeeder
