# Food Truck Reservation API

Ce projet est une API de gestion des réservations pour des food trucks, développée avec Symfony. L'API permet aux utilisateurs de créer, supprimer et lister des réservations pour différents food trucks.

## Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Technologies utilisées](#technologies-utilisées)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Endpoints](#endpoints)
- [Docker](#docker)
- [Makefile](#makefile)
- [Contribution](#contribution)
- [License](#license)

## Fonctionnalités

- **Ajouter une réservation** pour un food truck à une date spécifique.
- **Supprimer une réservation** existante en utilisant l'ID de la réservation.
- **Lister les réservations** pour une date donnée.

## Technologies utilisées

- Symfony
- Doctrine ORM
- MySQL
- Docker
- PHP 8.1
- Nginx

## Installation

Pour installer ce projet, suivez les étapes ci-dessous :

1. Clonez le dépôt :

   ```bash
   git clone https://github.com/tchoulom/foodTruck.git
   cd foodTruck
   ```

2. Assurez-vous d'avoir Docker et Docker Compose installés sur votre machine.

3. Construisez et démarrez les conteneurs :

   ```bash
   make up
   ```

4. Installez les dépendances PHP avec Composer :

   ```bash
   make exec cmd="composer install"
   ```

## Utilisation

Après avoir démarré l'application, vous pouvez utiliser les points de terminaison API suivants :

## Endpoints

- **Ajouter une réservation**
    - **URL** : `/reservation`
    - **Méthode** : `POST`
    - **Corps de la requête** (JSON) :
      ```json
      {
        "foodTruckName": "Nom du Food Truck",
        "date": "YYYY-MM-DD"
      }
      ```

- **Supprimer une réservation**
    - **URL** : `/reservation/{id}`
    - **Méthode** : `DELETE`

- **Lister les réservations par jour**
    - **URL** : `/reservations/{date}`
    - **Méthode** : `GET`
    - **Format de la date** : `YYYY-MM-DD`

## Docker

Le projet utilise Docker pour l'orchestration des services. Voici la configuration de base :

- **PHP** : Exécute l'application Symfony
- **Nginx** : Serveur web
- **MySQL** : Base de données
- **PhpMyAdmin** : Interface pour gérer la base de données

### Configuration des ports

- **Nginx** : `8080`
- **PhpMyAdmin** : `8081`
- **MySQL** : `3306`

## Makefile

Le projet inclut un `Makefile` pour simplifier les opérations courantes. Voici les commandes disponibles :

- **help** : Afficher l'aide
- **build** : Construire les conteneurs
- **up** : Lancer les conteneurs
- **down** : Arrêter les conteneurs
- **exec** : Exécuter une commande dans le conteneur PHP
- **bash** : Ouvrir un terminal bash dans le conteneur PHP
- **logs** : Afficher les logs des conteneurs
- **migrate** : Exécuter les migrations de base de données
- **seed** : Charger les données de test ou initiales
- **reset** : Réinitialiser la base de données
- **test** : Lancer les tests

### Exemples d'utilisation

Pour utiliser le `Makefile`, voici quelques exemples de commandes :

- Pour construire les conteneurs :
   ```bash
   make build
   ```

- Pour arrêter les conteneurs :
   ```bash
   make down
   ```

- Pour lancer les migrations de base de données :
   ```bash
   make migrate
   ```

## Contribution

## License

Ce projet est sous la [MIT License](LICENSE).
```