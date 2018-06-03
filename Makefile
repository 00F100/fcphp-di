.PHONY: composer run_composer

docker-composer: run_composer

run_composer:
	if [ ! -f composer.lock ]; then \
		sudo docker run -v $(PWD):/data php7.2-utils composer install; \
	else \
		sudo docker run -v $(PWD):/data php7.2-utils composer update; \
	fi;