# Déploiement complet Laravel sur le serveur
deploy:
	ssh -p 21098 kitsopmg@kit-services.org '\
		cd ~/public_html/kit-services && \
		git pull  && \
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
