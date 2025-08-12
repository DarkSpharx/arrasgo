# README pour le projet Arras Go

## Présentation du projet

Arras Go est une application web interactive permettant aux utilisateurs de découvrir et d’interagir avec différents parcours. L’application comprend un frontend pour l’expérience utilisateur, un backend pour la gestion des données, et un backoffice pour l’administration.

## Structure du projet

Le projet est organisé en plusieurs dossiers, chacun ayant un rôle spécifique :

- **frontend/** : Partie publique de l’application accessible aux utilisateurs.
  -- ...existing code...
  DROP TABLE IF EXISTS indices;
  DROP TABLE IF EXISTS etapes;
  -- ...existing code...

  - `index.html` : Page d’accueil du site.
  - `parcours.html` : Présentation des parcours disponibles.
  - `jeu.html` : Page de jeu pour les parcours actifs.
  - **css/** : Styles pour le site public.
    - `style.css` : Styles généraux des pages publiques.
  - **js/** : Scripts JavaScript pour les interactions utilisateur.
    - `script.js` : Gère les interactions des cartes, chronomètres et questions.

- **backend/** : Scripts PHP connectés à la base de données.

  - **config/** : Fichiers de configuration pour la connexion à la base de données.
    - `database.php` : Établit une connexion PDO à MariaDB.
  - **functions/** : Fonctions liées à la gestion des parcours.
    - `parcours.php` : Fonctions CRUD pour les parcours.
  - **api/** : Scripts d’API.
    - `get_parcours.php` : Exemple d’API REST retournant les parcours disponibles.
  - **security/** : Scripts liés à la sécurité.
    - `check_auth.php` : Vérifie si l’admin est connecté via la session.

- **backoffice/** : Interface d’administration sécurisée.

  - `login.php` : Formulaire de connexion administrateur.
  - `logout.php` : Script de déconnexion admin.
  - `dashboard.php` : Page principale du backoffice affichant les statistiques de gestion.
  - `add_parcours.php` : Formulaire d’ajout de nouveaux parcours.
  - `list_parcours.php` : Liste et gestion des parcours existants.
  - **css/** : Styles spécifiques au backoffice.
    - `admin.css` : Styles pour l’interface admin.
  - **js/** : Scripts JavaScript pour les interactions admin.
    - `admin.js` : Gère les modales, confirmations et autres interactions admin.
  - **assets/** : Icônes et images pour le backoffice.
    - **icons/** : Icônes spécifiques à l’interface admin.

- **data/** : Données non publiques.

  - **uploads/** : Dossier pour les éventuels fichiers uploadés (images, audio, etc.).

- **sql/** : Scripts SQL pour la gestion de la base de données.

  - `arrasgo_create_tables.sql` : Script complet de création de la base.

- **.env** : Variables d’environnement (ex : DB_USER, DB_PASS).

- **.gitignore** : Fichiers et dossiers à ignorer dans Git (ex : données sensibles).

## Installation

1. Clonez le dépôt sur votre machine locale.
2. Installez la base de données avec le script SQL situé dans `sql/arrasgo_create_tables.sql`.
3. Configurez vos variables d’environnement dans le fichier `.env`.
4. Accédez à l’application via un serveur web compatible PHP.

## Objectifs

- Offrir une plateforme interactive pour explorer et jouer à différents parcours.
- Garantir un accès sécurisé aux administrateurs pour la gestion de l’application.
- Maintenir un code propre et organisé pour faciliter les évolutions futures.

## Licence

Ce projet est sous licence MIT - voir le fichier LICENSE pour plus de détails.

## Présentation du projet

## Structure de la base de données

Le script SQL `sql/arrasgo_create_tables.sql` crée toutes les tables nécessaires :

- `users_admins` : gestion des administrateurs
- `parcours` : parcours du jeu
- `etapes` : étapes des parcours
- `chapitres` : chapitres liés aux étapes
- `personnages` : personnages du jeu
- `sessions` : sessions anonymes des joueurs

Pour initialiser la base, exécute la commande suivante dans MariaDB/MySQL :

```bash
mysql -u root -p < sql/arrasgo_create_tables.sql
```
