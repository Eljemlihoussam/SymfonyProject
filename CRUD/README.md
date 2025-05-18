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

# CRM Symfony avec Monitoring

## Description
Ce projet est un CRM développé avec Symfony qui permet aux utilisateurs de gérer leurs clients et leurs factures. Le projet inclut également une configuration complète de monitoring avec Prometheus et Grafana.

## Fonctionnalités
- Gestion des utilisateurs (authentification)
- Gestion des clients
- Gestion des factures
- Monitoring avec Prometheus et Grafana

## Prérequis
- Docker et Docker Compose
- Git
- Jenkins (pour CI/CD)
- SonarQube
- Ansible

## Installation

1. Cloner le repository :
```bash
git clone [URL_DU_REPO]
cd [NOM_DU_PROJET]
```

2. Lancer les conteneurs Docker :
```bash
docker-compose up -d
```

3. Installer les dépendances PHP :
```bash
docker-compose exec php composer install
```

4. Créer la base de données :
```bash
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console doctrine:migrations:migrate
```

## Accès aux services
- Application Symfony : http://localhost:8080
- PHPMyAdmin : http://localhost:8081
- Prometheus : http://localhost:9090
- Grafana : http://localhost:3000 (admin/admin)

## Monitoring
Le projet inclut une configuration complète de monitoring avec :

### Prometheus
- Collecte des métriques système
- Surveillance des services (PHP, Nginx, MySQL)
- Interface web accessible sur le port 9090

### Grafana
- Tableaux de bord personnalisables
- Visualisation des métriques
- Alertes configurables
- Interface web accessible sur le port 3000

## CI/CD
Le projet utilise Jenkins pour l'automatisation du déploiement :
1. Clonage du code depuis GitLab
2. Installation des dépendances et nettoyage du cache
3. Analyse statique avec SonarQube
4. Build de l'image Docker
5. Push vers DockerHub
6. Déploiement via Ansible

## Structure du projet
```
.
├── docker/
│   ├── prometheus/
│   │   └── prometheus.yml
│   └── grafana/
│       └── provisioning/
│           ├── datasources/
│           └── dashboards/
├── src/
├── templates/
├── config/
├── public/
├── docker-compose.yml
├── Dockerfile
├── Jenkinsfile
└── README.md
```

## Sécurité
- Authentification requise pour accéder à l'application
- Chaque utilisateur ne peut voir que ses propres clients et factures
- Validation des formulaires
- Protection CSRF
- Sécurisation des routes

## Contribution
Pour contribuer au projet :
1. Fork le repository
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Créer une Pull Request

## Licence
[Votre licence ici] 

docker-compose down

.\init-config.ps1

# Initialiser la configuration Prometheus
docker run --rm --entrypoint "" -v crud_prometheus_config:/etc/prometheus -v "$PWD/docker/prometheus/prometheus.yml:/prometheus.yml" prom/prometheus:latest cp /prometheus.yml /etc/prometheus/prometheus.yml

# Initialiser la configuration Nginx
docker run --rm --entrypoint "" -v crud_nginx_config:/etc/nginx/conf.d -v "$PWD/docker/nginx/default.conf:/default.conf" nginx:alpine cp /default.conf /etc/nginx/conf.d/default.conf 