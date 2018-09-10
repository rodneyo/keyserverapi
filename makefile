ifndef PROJECT
$(error PROJECT is not set, add 'PROJECT=<name>' to make command)
endif

default:
	#Usage:
	#'sudo make compose-up' - Builds Keyserver Docker images, starts the containers, and adds their IP addresses to your /etc/add_hosts file.
	#'sudo make compose-destroy-cont' - Stops Docker containers that were started by the Keyserver Docker Compose, and deletes them.
	#'sudo make compose-destroy-all' - Stops Docker containers that were started by the Keyserver Docker Compose, deletes them, and also deletes the images that were built.
compose-up:
	CURRENT_USER=$(shell id -u); \
    CURRENT_USER=$$CURRENT_USER PROJECT=$$PROJECT docker-compose up
compose-destroy-cont:
	CURRENT_USER=$(shell id -u); \
	for container in `docker-compose ps -q`; do \
		CONT_PROJECT=`docker inspect -f '{{ index .Config.Labels "PROJECT" }}' $$container`; \
		if [ $$CONT_PROJECT = $$PROJECT ]; then \
			docker stop $$container; \
			docker rm $$container; \
		fi \
	done
compose-destroy-all:
	CURRENT_USER=$(shell id -u); \
	for container in `docker-compose ps -q`; do \
		CONT_PROJECT=`docker inspect -f '{{ index .Config.Labels "PROJECT" }}' $$container`; \
		if [ $$CONT_PROJECT = $$PROJECT ]; then \
			docker stop $$container; \
			docker rm $$container; \
		fi \
	done
	for image in `docker images -q`; do \
		CONT_PROJECT=`docker inspect -f '{{ index .Config.Labels "PROJECT" }}' $$image`; \
        if [ $$CONT_PROJECT = $$PROJECT ]; then \
        	docker rmi $$image; \
        fi \
	done