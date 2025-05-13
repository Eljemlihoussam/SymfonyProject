# CRM Symfony - Gestion des Clients et Factures

## Description
Ce projet est un CRM (Customer Relationship Management) développé avec Symfony. Il permet aux utilisateurs de gérer leurs clients et leurs factures de manière sécurisée et efficace.

## Fonctionnalités
- Authentification sécurisée
- Gestion des clients
- Gestion des factures
- Interface responsive avec Bootstrap
- Séparation des données par utilisateur

## Prérequis
- PHP 8.2 ou supérieur
- Composer
- Docker et Docker Compose
- MySQL 8.0
- Nginx

## Installation

### 1. Cloner le projet
```bash
git clone [URL_DU_REPO]
cd CRUD
```

### 2. Installation des dépendances
```bash
composer install
```

### 3. Configuration de l'environnement
```bash
cp .env .env.local
```
Modifier les variables d'environnement dans `.env.local` selon votre configuration.

### 4. Base de données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Lancer avec Docker
```bash
docker-compose up -d
```

## Structure du Projet

### Entités
- **User** : Gestion des utilisateurs
- **Client** : Gestion des clients
- **Facture** : Gestion des factures

### Contrôleurs
- SecurityController : Gestion de l'authentification
- UserController : Gestion des utilisateurs
- ClientController : Gestion des clients
- FactureController : Gestion des factures
- DashboardController : Tableau de bord

## CI/CD

### Jenkins Pipeline
Le projet utilise Jenkins pour l'intégration continue avec les étapes suivantes :
1. Clone du code depuis GitLab
2. Installation des dépendances et nettoyage du cache
3. Analyse statique avec SonarQube
4. Build de l'image Docker
5. Push vers DockerHub
6. Déploiement via Ansible

### SonarQube
- Analyse statique du code
- Métriques de qualité
- Rapports de couverture de code

### Ansible
- Déploiement automatique
- Configuration de l'environnement
- Gestion des conteneurs Docker

## Sécurité
- Authentification sécurisée
- Protection CSRF
- Validation des données
- Séparation des données par utilisateur

## Technologies Utilisées
- Symfony 6.x
- PHP 8.2
- MySQL 8.0
- Docker & Docker Compose
- Jenkins
- SonarQube
- Ansible
- Bootstrap 5

## Contribution
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence
Ce projet est sous licence MIT. 