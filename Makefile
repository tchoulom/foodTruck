# Variables
APP_NAME = foodTruck
DOCKER_COMPOSE = docker-compose
DOCKER_COMPOSE_FILE = $(DOCKER_COMPOSE).yml
PHP_CONTAINER = php
WEB_SERVER = webserver
DB_CONTAINER = db
PHPMYADMIN_CONTAINER = phpmyadmin
SYMFONY_ENV = dev

# Commandes

.PHONY: help build up down exec bash logs migrate seed reset test

# Afficher l'aide
help:
	@echo "Makefile pour le projet $(APP_NAME)"
	@echo ""
	@echo "Commandes disponibles :"
	@echo "  build           : Construire les conteneurs"
	@echo "  up              : Lancer les conteneurs"
	@echo "  down            : Arrêter les conteneurs"
	@echo "  exec            : Exécuter une commande dans le conteneur PHP"
	@echo "  bash            : Ouvrir un terminal bash dans le conteneur PHP"
	@echo "  logs            : Afficher les logs des conteneurs"
	@echo "  migrate         : Exécuter les migrations de base de données"
	@echo "  seed            : Charger les données de test ou initiales"
	@echo "  reset           : Réinitialiser la base de données"
	@echo "  test            : Lancer les tests"

# Construire les conteneurs
build:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) build

# Lancer les conteneurs
up:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) up -d

# Arrêter les conteneurs
down:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) down

# Exécuter une commande dans le conteneur PHP
exec:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console $(cmd)

# Ouvrir un terminal bash dans le conteneur PHP
bash:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) bash

# Afficher les logs des conteneurs
logs:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) logs -f

# Exécuter les migrations de base de données
migrate:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate

# Charger les données de test ou initiales
seed:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console doctrine:fixtures:load

# Réinitialiser la base de données
reset: down
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) up -d --build
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console doctrine:database:create
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/console doctrine:fixtures:load

# Lancer les tests
test:
	$(DOCKER_COMPOSE) -f $(DOCKER_COMPOSE_FILE) exec $(PHP_CONTAINER) php bin/phpunit
