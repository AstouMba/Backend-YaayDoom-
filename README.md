# YaayDoom Backend

Backend Laravel de l'application YaayDoom, centre sur la gestion des parcours de sante pour les roles `maman`, `professionnel` et `admin`.

Le projet expose une API securisee par token via Laravel Passport, avec documentation OpenAPI/Swagger integree et une organisation par features metier.

## Sommaire

- [Stack technique](#stack-technique)
- [Fonctionnalites](#fonctionnalites)
- [Architecture du projet](#architecture-du-projet)
- [Prerequis](#prerequis)
- [Installation](#installation)
- [Configuration `.env`](#configuration-env)
- [Comptes de demo](#comptes-de-demo)
- [Lancer le projet](#lancer-le-projet)
- [API](#api)
- [Swagger](#swagger)
- [Base de donnees et seeders](#base-de-donnees-et-seeders)
- [Tests](#tests)
- [Commandes utiles](#commandes-utiles)

## Stack technique

- Laravel 12
- PHP 8.2+
- Laravel Passport pour l'authentification par bearer token
- L5 Swagger pour la documentation OpenAPI
- PostgreSQL en base par defaut
- Vite et Tailwind CSS pour les assets frontend si necessaire

## Fonctionnalites

- Authentification:
  - inscription maman en une étape
  - inscription professionnel en plusieurs étapes avec validation admin
  - connexion de la maman par téléphone
  - connexion du professionnel et de l'admin par email
  - upload des documents médicaux du professionnel
  - deconnexion
  - consultation du profil connecte
  - mise a jour du profil
  - changement de mot de passe
- Administration:
  - liste des utilisateurs
  - statistiques
  - validation / rejet des professionnels
  - changement de role
  - changement de statut
- Metier:
  - grossesses
  - bebes
  - consultations
  - vaccinations
  - rendez-vous
  - cartes
  - scans

## Architecture du projet

Le code suit une organisation par domaine fonctionnel dans `app/Features/`.

Exemples:

- `app/Features/Auth` pour l'authentification
- `app/Features/Admin` pour les usages administrateur
- `app/Features/Grossesse`, `Bebe`, `Vaccination`, `RendezVous`, `Consultation`, `Carte`, `Scan` pour les modules metier
- `app/Http/Middleware/RoleMiddleware.php` pour la gestion des roles
- `app/Swagger/OpenApiSpec.php` pour la documentation Swagger

Le point de montage principal de l'application se trouve dans `bootstrap/app.php`, avec un alias de middleware `role`.

## Prerequis

- PHP 8.2 ou superieur
- Composer
- Node.js et npm
- PostgreSQL

## Installation

1. Installer les dependances PHP:

```bash
composer install
```

2. Creer le fichier d'environnement:

```bash
cp .env.example .env
```

3. Generer la cle d'application:

```bash
php artisan key:generate
```

4. Configurer la base de donnees dans `.env`:

- `DB_CONNECTION=pgsql`
- `DB_URL=postgresql://username:password@host/database?sslmode=require`
- `DB_SSLMODE=require`

5. Lancer les migrations et les seeders:

```bash
php artisan migrate --seed
```

6. Initialiser Passport si tes cles OAuth ne sont pas deja presentes dans l'environnement:

```bash
php artisan passport:install
```

7. Installer les dependances frontend si necessaire:

```bash
npm install
```

## Configuration `.env`

Les variables les plus importantes pour ce backend sont:

```env
APP_NAME=YaayDoom
APP_URL=http://127.0.0.1:8000
APP_DEBUG=true

DB_CONNECTION=pgsql
DB_URL=postgresql://username:password@host/database?sslmode=require
DB_SSLMODE=require

CORS_ALLOWED_ORIGINS=https://yaaydoom.vercel.app,http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000
L5_SWAGGER_UI_PERSIST_AUTHORIZATION=true

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

Notes utiles:

- ajuste `CORS_ALLOWED_ORIGINS` selon l'URL du frontend, par exemple `https://yaaydoom.vercel.app`
- ajuste `L5_SWAGGER_CONST_HOST` si l'API tourne sur un autre host ou port
- `L5_SWAGGER_GENERATE_ALWAYS=true` force la regeneration de la spec Swagger a chaque execution

## Comptes de demo

Les seeders ajoutent des comptes de demonstration directement utilisables.

| Role | Email | Mot de passe |
| --- | --- | --- |
| Admin | `admin@demo.com` | `demo1234` |
| Maman | `+221771234567` | `demo1234` |
| Professionnel | `pro@demo.com` | `demo1234` |
| Professionnel en attente | `pro.enattente@demo.com` | `demo1234` |

Le compte `pro.enattente@demo.com` est cree avec `is_validated = false`.
La maman de demo peut aussi se connecter avec son telephone `+221771234567`.

## Lancer le projet

### Mode developpement

Le script suivant lance le serveur Laravel, la queue, les logs et Vite en parallele:

```bash
composer run dev
```

### Mode manuel

Tu peux aussi demarrer chaque service separement:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
npm run dev
```

## API

L'API est exposee sous le prefixe `/api`.

### Authentification

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`
- `PATCH /api/auth/me`
- `POST /api/auth/change-password`
- `POST /api/auth/professional/documents`

Les documents de validation du professionnel sont stockés dans `storage/app/public/professionnels/...` et exposés via une URL publique dans le contrat API. Le front peut envoyer plusieurs fichiers avec le champ `documents[]`.

### Administration

Accessibles uniquement pour les utilisateurs ayant le role `admin`.

- `GET /api/admin/users`
- `GET /api/admin/stats`
- `GET /api/admin/professionnels/pending`
- `POST /api/admin/professionnels/{user}/approve` avec `{ "motif": "Documents conformes" }`
- `POST /api/admin/professionnels/{user}/reject` avec `{ "motif": "Document incomplet" }`
- `PATCH /api/admin/users/{user}/role`
- `PATCH /api/admin/users/{user}/status`
- `GET /api/users/{user}`
- `PUT /api/users/{user}`
- `DELETE /api/users/{user}`

Les fiches admin d'un professionnel renvoient maintenant aussi `decisionStatus`, `decisionMotif`, `decisionDate`, `decisionBy`, ainsi que les documents de vérification.

### Parcours metier

Toutes les routes suivantes sont protegees par `auth:api`.

- `grossesses`
  - `GET /api/grossesses`
  - `GET /api/grossesses/{grossesse}`
  - `POST /api/grossesses`
  - `PUT /api/grossesses/{grossesse}`
  - `PATCH /api/grossesses/{grossesse}`
  - `DELETE /api/grossesses/{grossesse}`
- `bebes`
  - `GET /api/bebes`
  - `GET /api/bebes/{bebe}`
  - `POST /api/bebes`
  - `PUT /api/bebes/{bebe}`
  - `PATCH /api/bebes/{bebe}`
  - `DELETE /api/bebes/{bebe}`
- `vaccinations`
  - `GET /api/vaccinations`
  - `GET /api/vaccinations/{vaccination}`
  - `POST /api/vaccinations`
  - `PUT /api/vaccinations/{vaccination}`
  - `PATCH /api/vaccinations/{vaccination}`
  - `DELETE /api/vaccinations/{vaccination}`
- `rendez-vous`
  - `GET /api/rendez-vous`
  - `GET /api/rendez-vous/{rendezVous}`
  - `POST /api/rendez-vous`
  - `PUT /api/rendez-vous/{rendezVous}`
  - `PATCH /api/rendez-vous/{rendezVous}`
  - `DELETE /api/rendez-vous/{rendezVous}`
- `cartes`
  - `GET /api/cartes`
  - `GET /api/cartes/{carte}`
  - `POST /api/cartes`
  - `PUT /api/cartes/{carte}`
  - `PATCH /api/cartes/{carte}`
  - `DELETE /api/cartes/{carte}`
- `consultations`
  - `GET /api/consultations`
  - `GET /api/consultations/{consultation}`
  - `POST /api/consultations`
  - `PUT /api/consultations/{consultation}`
  - `PATCH /api/consultations/{consultation}`
  - `DELETE /api/consultations/{consultation}`
- `scans`
  - `GET /api/scans`
  - `POST /api/scans/resolve`
  - `GET /api/scans/{scan}`
  - `DELETE /api/scans/{scan}`

### Roles et securite

- l'authentification API utilise le guard `passport`
- le middleware `role` est declare dans `bootstrap/app.php`
- le format attendu pour les appels proteges est `Authorization: Bearer <token>`
- health check disponible sur `GET /up`

## Swagger

La documentation API est generee avec L5 Swagger.

- URL locale: `http://127.0.0.1:8000/api/documentation`
- spec JSON: `api-docs.json`

Les annotations OpenAPI se trouvent dans `app/Swagger/OpenApiSpec.php`.

## Base de donnees et seeders

Tables et entites principales:

- `users`
- `grossesses`
- `bebes`
- `consultations`
- `vaccinations`
- `rendez_vous`
- `cartes`
- `scans`
- tables OAuth de Passport

Le seeder principal `DatabaseSeeder` charge:

1. `AdminSeeder`
2. `MamanSeeder`
3. `ProfessionnelSeeder`
4. `GrossesseSeeder`
5. `BebeSeeder`
6. `ConsultationSeeder`
7. `RendezVousSeeder`
8. `CarteSeeder`
9. `VaccinationSeeder`
10. `ScanSeeder`

## Tests

Lance la suite de tests avec:

```bash
composer run test
```

## Commandes utiles

- `php artisan migrate:fresh --seed` pour repartir de zero avec les donnees de demo
- `php artisan tinker` pour explorer les modeles et les donnees
- `php artisan pail` pour suivre les logs Laravel
- `php artisan up` et `php artisan down` pour la maintenance
- `php artisan route:list` pour verifier les routes exposees
- `npm run build` pour produire les assets frontend de production

## Docker

Le backend peut etre embarque dans une image Docker de production.

### Build de l'image

```bash
docker build -t yaaydoom-backend:latest .
```

### Lancement du conteneur

Avant de demarrer, assure-toi de fournir une `APP_KEY` valide dans ton `.env` ou via les variables d'environnement du conteneur.

```bash
docker run -d \
  --name yaaydoom-backend \
  --env-file .env \
  -p 8000:8000 \
  yaaydoom-backend:latest
```

### Avec Docker Compose

Le projet utilise maintenant un seul fichier `docker-compose.yml`.

```bash
docker compose up -d --build
```

Notes:

- `USE_MOCK_DATA=true` remplit automatiquement la base avec les donnees de demo au demarrage
- `APP_PORT=8000` peut etre change si le port est deja utilise
- `APP_URL` et `L5_SWAGGER_CONST_HOST` doivent correspondre a l'URL publique du service
- si tu relies le backend a PostgreSQL, verifie que `DB_URL` pointe vers la bonne instance
- par defaut, `docker-compose.yml` utilise aussi l'image Docker Hub `lingueredev/yaaydoom-backend:latest`
- pour forcer l'image publiee sans rebuild local, utilise `DOCKER_IMAGE=lingueredev/yaaydoom-backend:latest docker compose up -d --no-build`

### Pousser sur Docker Hub

Avec ton identifiant `lingueredev`, tu peux publier l'image comme ceci:

```bash
docker login
docker build -t lingueredev/yaaydoom-backend:latest .
docker push lingueredev/yaaydoom-backend:latest
```

### Lancer le conteneur publie

Si tu veux lancer l'image Docker Hub sans rebuild local:

```bash
DOCKER_IMAGE=lingueredev/yaaydoom-backend:latest docker compose up -d --no-build
```

### Recuperer le JSON OpenAPI

Si tu veux le JSON brut de la spec, utilise:

```text
/openapi.json
```

Il correspond au fichier `storage/api-docs/api-docs.json` genere par L5 Swagger.

## Deploiement sur Render via une image Docker Hub

Tu peux deployer ce backend sur Render en utilisant l'image publiee sur Docker Hub.

### 1. Construire et pousser l'image

Si tu es sur une machine x86_64 classique:

```bash
docker build -t lingueredev/yaaydoom-backend:latest .
docker push lingueredev/yaaydoom-backend:latest
```

Si tu veux forcer une image compatible Render depuis n'importe quelle machine:

```bash
docker buildx build --platform linux/amd64 -t lingueredev/yaaydoom-backend:latest --push .
```

### 2. Créer le service Render

1. Crée un nouveau `Web Service` sur Render.
2. Choisis `Deploy an existing image`.
3. Renseigne `lingueredev/yaaydoom-backend:latest`.
4. Ne mets ni build command ni start command.
5. Render fournira automatiquement `PORT`, et le conteneur l’utilise deja.

### 3. Ajouter la base PostgreSQL

1. Ajoute une base `PostgreSQL` Render au meme projet.
2. Recupere sa `Connection String`.
3. Renseigne-la dans le service web via `DB_URL`.

### Variables d'environnement à configurer

Dans le service web Render, ajoute au minimum:

```env
APP_NAME=YaayDoom
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate on Render or paste a valid key>
APP_URL=https://<ton-service>.onrender.com
DB_CONNECTION=pgsql
DB_URL=<connection string Render Postgres>
DB_SSLMODE=require
RUN_MIGRATIONS=true
RUN_PASSPORT_SETUP=true
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
L5_SWAGGER_GENERATE_ALWAYS=false
L5_SWAGGER_CONST_HOST=https://<ton-service>.onrender.com
LOG_LEVEL=info
```

### Variables utiles côté frontend

Si ton frontend est séparé, ajoute aussi:

```env
CORS_ALLOWED_ORIGINS=https://<ton-frontend>.onrender.com
```

### Migration

Pour ce projet, le conteneur peut lancer les migrations au démarrage si tu defines `RUN_MIGRATIONS=true`.
Il peut aussi s'assurer que Passport est prêt via `RUN_PASSPORT_SETUP=true` ou automatiquement en `APP_ENV=production`.

Si tu prefere le faire manuellement dans Render, lance:

```bash
php artisan migrate --force
```

### Points importants

- le conteneur ecoute la variable `PORT` fournie par Render
- la base de donnees doit venir de Render Postgres, pas de Neon externe
- `APP_KEY` est obligatoire pour que l’application démarre correctement
- `USE_MOCK_DATA` doit rester désactivé en production
- si tu changes le domaine du service, mets aussi à jour `APP_URL` et `L5_SWAGGER_CONST_HOST`

## Licence

Ce projet est distribue sous licence MIT.
