# Configuration de la Sécurité (security.yaml)

Ce document explique la configuration de sécurité de l'application Symfony.

## 1. Configuration des Mots de Passe

```yaml
password_hashers:
    Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
```
- Gère le hachage des mots de passe
- `'auto'` : Symfony choisit automatiquement l'algorithme le plus sécurisé

## 2. Configuration des Utilisateurs

```yaml
providers:
    app_user_provider:
        entity:
            class: App\Entity\User
            property: username
```
- Définit comment les utilisateurs sont chargés
- Utilise l'entité `User` pour l'authentification
- L'identifiant de connexion est le champ `username`

## 3. Configuration des Firewalls

```yaml
firewalls:
    dev:
        pattern: ^/(_(profiler|wdt)|css|images|js)/
        security: false
    main:
        lazy: true
        provider: app_user_provider
        form_login:
            login_path: app_login
            check_path: app_login_check
            enable_csrf: true
            username_parameter: _username
            password_parameter: _password
            default_target_path: app_dashboard
        logout:
            path: app_logout
            target: app_login
```
- `dev` : Désactive la sécurité pour les outils de développement
- `main` : Configuration principale de sécurité
  - `form_login` : Configuration du formulaire de connexion
  - `logout` : Configuration de la déconnexion

## 4. Contrôle d'Accès

```yaml
access_control:
    - { path: ^/login, roles: PUBLIC_ACCESS }
    - { path: ^/admin, roles: ROLE_ADMIN }
    - { path: ^/client, roles: ROLE_ADMIN }
    - { path: ^/dashboard, roles: ROLE_USER }
    - { path: ^/facture, roles: ROLE_USER }
    - { path: ^/, roles: ROLE_USER }
```
- Définit les règles d'accès aux différentes parties de l'application
- `/login` : Accessible à tous
- `/admin` : Réservé aux administrateurs
- `/client` : Réservé aux administrateurs
- `/dashboard` : Accessible aux utilisateurs authentifiés
- `/facture` : Accessible aux utilisateurs authentifiés
- `/` : Nécessite une authentification (ROLE_USER)

## 5. Hiérarchie des Rôles

```yaml
role_hierarchy:
    ROLE_ADMIN: ROLE_USER
```
- Définit la hiérarchie des rôles
- `ROLE_ADMIN` hérite automatiquement de `ROLE_USER`
- Un administrateur a donc tous les droits d'un utilisateur normal

## 6. Voters

Le système utilise des Voters pour la sécurité fine :
- `ClientVoter` : Gère les permissions sur les clients
  - Un utilisateur peut voir/modifier/supprimer ses propres clients
  - Un administrateur peut tout faire

## Exemple d'Utilisation

1. **Connexion Utilisateur Normal (ROLE_USER)**
   - Accès au tableau de bord (`/dashboard`)
   - Accès à ses factures (`/facture`)
   - Ne peut pas accéder à `/admin` ni `/client`

2. **Connexion Administrateur (ROLE_ADMIN)**
   - Accès à tous les clients (`/client`)
   - Accès à l'administration (`/admin`)
   - Accès au tableau de bord et aux factures
   - Tous les droits d'un utilisateur normal

## Bonnes Pratiques

1. Toujours utiliser les Voters pour la sécurité fine
2. Ne jamais faire confiance aux données côté client
3. Vérifier les permissions à chaque niveau (contrôleur, service, template)
4. Utiliser les annotations `#[IsGranted]` pour la sécurité au niveau des contrôleurs 