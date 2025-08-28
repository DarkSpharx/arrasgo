
# README pour le projet Arras Go

## Présentation du projet

Arras Go est une application web interactive permettant aux utilisateurs de découvrir et d’interagir avec différents parcours. L’application comprend :
- un frontend pour l’expérience utilisateur,
- un backend pour la gestion des données,
- un backoffice pour l’administration.

## Structure du projet

Le projet est organisé en plusieurs dossiers :

- **frontend/** : Partie publique de l’application accessible aux utilisateurs.
  - `index.php` : Page d’accueil du site.
  - `list_parcours.php` : Liste des parcours disponibles.
  - `etape.php` : Affichage des étapes et chapitres d’un parcours.
  - **css/** : Styles pour le site public (`style.css`).
  - **js/** : Scripts JavaScript pour les interactions utilisateur (`script.js`).

- **backend/** : Scripts PHP connectés à la base de données.
  - **config/** : Connexion PDO à MariaDB (`database.php`).
  - **functions/** : Fonctions de gestion (parcours, étapes, chapitres, personnages).
  - **api/** : Endpoints d’API REST (`get_parcours.php`, `get_etapes.php`).
  - **security/** : Sécurité et authentification (`check_auth.php`).

- **backoffice/** : Interface d’administration sécurisée.
  - `login.php` / `logout.php` : Connexion et déconnexion admin.
  - `dashboard.php` : Statistiques et gestion rapide.
  - `add_*.php`, `edit_*.php`, `list_*.php`, `delete_*.php` : CRUD sur les entités.
  - **css/** : Styles spécifiques au backoffice (`style_backoffice.css`, `cards.css`, etc.).
  - **js/** : Interactions admin (`admin.js`).
  - **assets/icons/** : Icônes pour l’interface admin.

- **data/** : Données non publiques (uploads, images, mp3).
  - **uploads/** : Fichiers uploadés (images, audio, etc.).
  - **images/**, **mp3/** : Ressources médias utilisées dans les parcours.

- **sql/** : Scripts SQL pour la gestion de la base de données.
  - `arrasgo_create_tables.sql` : Script complet de création de la base.

- **.env** : Variables d’environnement (DB_USER, DB_PASS, etc.).
- **.gitignore** : Fichiers/dossiers à ignorer dans Git (ex : données sensibles).

## Installation

1. Clonez le dépôt sur votre machine locale.
2. Installez la base de données avec le script SQL situé dans `sql/arrasgo_create_tables.sql`.
3. Configurez vos variables d’environnement dans le fichier `.env`.
4. Accédez à l’application via un serveur web compatible PHP.

## Objectifs

- Offrir une plateforme interactive pour explorer et jouer à différents parcours.
- Garantir un accès sécurisé aux administrateurs pour la gestion de l’application.
- Maintenir un code propre et organisé pour faciliter les évolutions futures.

## Structure de la base de données

Le script SQL `sql/arrasgo_create_tables.sql` crée toutes les tables nécessaires :

- `users_admins` : gestion des administrateurs (id, nom, email, mot de passe, rôle, date de création)
- `parcours` : parcours du jeu (id, id_user, nom, description, image, statut, mode géo)
- `etapes` : étapes des parcours (id, id_parcours, titre, mp3, images, indices, question, réponse, coordonnées, ordre, type_validation)
- `chapitres` : chapitres liés aux étapes (id, id_etape, titre, texte, ordre, image)
- `personnages` : personnages du jeu (id, nom, image, description)
- `sessions` : sessions anonymes des joueurs (id_session, date_creation, derniere_etape_validee)
- `parcours_personnages` : table de liaison parcours/personnages

Pour initialiser la base, exécute la commande suivante dans MariaDB/MySQL :

```bash
mysql -u root -p < sql/arrasgo_create_tables.sql
```

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
