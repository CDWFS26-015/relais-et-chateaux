# Relais & Châteaux - Application de gestion des événements

## Table des matières
1. [Installation et lancement](#1-installation-et-lancement)
2. [Identifiants de test](#2-identifiants-de-test)
3. [Base de données](#3-base-de-données)
4. [Pourquoi Symfony](#4-pourquoi-symfony)
5. [Liens utiles](#5-liens-utiles)

---

## 1. Installation et lancement

### Prérequis
- PHP ≥ 8.1
- Composer
- Symfony CLI
- MySQL ou MariaDB
- Node.js + npm (optionnel pour compiler les assets)

### Étapes d’installation

1. Cloner le projet :
```bash
git clone <lien-du-repo> RelaisEtChateaux
cd RelaisEtChateaux
```
2. Installer les dépendances PHP :
```bash
composer install
```
3. Configurer la base de données :

Modifier le fichier .env ou .env.local pour configurer la connexion à votre base de données :

<pre> ``` DATABASE_URL="mysql://root:password@127.0.0.1:3306/relais_et_chateaux?serverVersion=8.0" ``` </pre>

Créer la base de données :
```bash
php bin/console doctrine:database:create
```
Appliquer les migrations :
```bash
php bin/console doctrine:migrations:migrate
```
4. Charger les données de test (fixtures) :
```bash
php bin/console doctrine:fixtures:load
```
5. Lancer le serveur Symfony :
```bash
symfony server:start
```
L’application sera accessible à l’adresse : http://127.0.0.1:8000

---

## 2. Identifiants de test

Rôle                 | Email                 | Mot de passe
----------------------|---------------------|--------------
Administrateur       | admin@test.com       | adminpass
Responsable          | responsable@test.com | resp1234
Utilisateur standard | user@test.com        | user1234

---

## 3. Base de données

- La base de données peut être recréée via Doctrine et les fixtures.
- Les tables principales :

user
evenement
avis

- Les fixtures chargent automatiquement les utilisateurs de test et quelques événements et avis.

---

## 4. Pourquoi Symfony

Symfony a été choisi pour ce projet car il offre :

1. Architecture MVC robuste, facilitant la séparation des responsabilités (contrôleurs, entités, vues).  
2. Sécurité intégrée (gestion des rôles, encodage des mots de passe, CSRF, etc.), adaptée à la gestion multi-rôles.  
3. Doctrine ORM pour gérer facilement les bases de données relationnelles avec un mapping objet-relationnel.  
4. EasyAdmin Bundle pour générer rapidement un back-office fonctionnel et sécurisé.  
5. Une documentation complète et une communauté active, réduisant les risques techniques et accélérant le développement.

---

## 5. Liens utiles

- Symfony Docs : https://symfony.com/doc/current/index.html
- Doctrine ORM Docs : https://www.doctrine-project.org/projects/orm.html
- EasyAdmin Bundle Docs : https://symfony.com/bundles/EasyAdminBundle/current/
