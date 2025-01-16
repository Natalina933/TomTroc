***---TOM TROC - Plateforme d'échange de livres---***
TOM TROC est une plateforme web permettant aux utilisateurs d'échanger des livres entre eux.

**Installation**
1-Clonez le dépôt du projet dans votre environnement local.
2-Importez le fichier site_tom_troc (10).sql dans votre base de données MySQL via phpMyAdmin.
3-Configurez les paramètres de connexion à la base de données dans le dossier config, le fichier de config.php

**Configuration de la base de données**
    . Nom de la base de données: site_tom_troc
    . Utilisez phpMyAdmin pour importer le fichier SQL fourni.

**Accès au site**
Pour tester la connexion, utilisez par exemple:
    . Identifiant : 1230@free.fr
    . Mot de passe : 1230
*Note*: Pour faciliter les tests, le mot de passe est dans l'identifiant car les mots de passe sont cryptés dans la base de données.

**Structure du projet**
Le projet s'articule autour de trois tables principales :
    . book : informations sur les livres disponibles
    . user : données des utilisateurs
    . message : messages échangés entre utilisateurs

**Fonctionnalités principales**
📚 Inscription et connexion des utilisateurs
🔍 Ajout et consultation de livres
💬 Système de messagerie entre utilisateurs
🔄 Gestion des échanges de livres

**Prérequis**
PHP 8.3.6 ou supérieur
MySQL 8.3.0 ou supérieur
Serveur web (par exemple, Apache)

**Stack**
Backend: PHP
Base de données: MySQL
Frontend: HTML/CSS/JavaScript (non inclus dans le dump SQL)
Pour toute problème lors de l'installation, n'hésitez pas à me contacter.