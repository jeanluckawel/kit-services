# Déploiement complet Laravel sur le serveur
deploy:
	ssh -p 21098 kitsopmg@kit-services.org '\
		cd ~/public_html/test/kit-services && \
		git pull && \
		composer install --no-dev --optimize-autoloader && \
		if [ ! -f .env ]; then cp .env.example .env; fi && \
		php artisan key:generate --force && \
		php artisan migrate:fresh --seed --step && \
		php artisan cache:clear && \
		php artisan config:cache && \
		php artisan config:cache && \
		php artisan optimize:clear  && \
		php artisan storage:link \
	'







### Deploy the application to the server
#deploy:
#	sssh -p 21098 kitsopmg@kit-services.org 'cd ~cd ~/public_html/test/KITSERVICES_  && git pull && make install'
#
#deploy-refresh:
#	ssh -p 21098 kitsopmg@kit-services.org 'cd ~cd ~/public_html/test/KITSERVICES_  && git pull && make install && make refresh'
#
#
#refresh:
#	php artisan migrate:fresh --seed --step
#
#
#install: .env vendor/autoload.php public/storage
#	php artisan migrate --step
#	php artisan cache:clear
#
#.env:
#	cp .env.example .env
#	php artisan key:generate
#
#
#vendor/autoload.php: composer.lock
#	composer install
#	touch vendor/autoload.php
#
#
#public/storage:
#	php artisan optimize:clear
#	php artisan storage:link
#

