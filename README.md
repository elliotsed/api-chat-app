<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 Installation

### Cloner le projet :
git clone https://github.com/elliotsed/api-chat-app
cd api-chat-app

### Installer les dépendances
composer install

### Configurer la base de données
Vérifier que le fichier .env est présent (normalement fourni dans le repo) et modifier les valeurs de la base de données et autres configurations si nécessaire :

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_chat_app
DB_USERNAME=votre_username
DB_PASSWORD=votre_password

### Exécuter les migrations
php artisan migrate

### Lancer le serveur
php artisan serve

## API prêt à tester avec Postman
Toutes les routes (sauf register et login) nécessitent un token Sanctum. Ajouter le token dans le header de la requête :
Authorization: Bearer {token}

### 🔐 Authentification

#### register
POST /api/register

Body :
{
  "name": "John",
  "email": "john@example.com",
  "password": "John@123"
}

#### login
POST /api/login

Body :
{
  "email": "john@example.com",
  "password": "John@123"
}

Réponse : contient un token à utiliser dans les prochaines requêtes.

#### logout
POST /api/logout

Authorization: Bearer {token}

### 💬 Discussions

#### Créer une discussion
POST /api/discussions

Body :
{
  "user_ids": [id]
}

Authorization: Bearer {token}

(où id est l’ID d’un utilisateur avec qui tu veux créer la discussion)

#### Lister les discussions de l’utilisateur connecté
GET /api/discussions

Authorization: Bearer {token}

### 📨 Messages

#### Envoyer un message
POST /api/discussions/{id}/messages

Body : 
{
  "content": "Hello"
}

Authorization: Bearer {token}

(où id est l’ID de la discussion)



#### Récupérer les messages d'une discussion
GET /api/discussions/{id}/messages

Authorization: Bearer {token}

(où id est l’ID de la discussion)






