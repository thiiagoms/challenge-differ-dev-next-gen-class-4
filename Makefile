.PHONY: up
up:
	docker compose up -d

.PHONY: stop
stop:
	docker compose stop

.PHONY: container
container:
	docker compose exec -u ${UID}:${GID} php bash
