<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Laravel API MICROSERVICES

This is a **Laravel API-only MICROSERVICES project**.  
It includes:

- Default **Client** (`CLIENT001` + dynamic token)  
- Default **Admin User** (`admin@gmail.com`)  
- Fully API-ready for React frontend or other clients  

---

## 1. Prerequisites

Make sure you have installed:

- PHP >= 8.1  
- Composer  
- MySQL

---

## 2. Clone / Pull Project

```bash
git clone <your-repo-url> laravel-api-gateway
cd laravel-api-gateway
```
---

## 3. Install Dependencies

```bash
composer install
```

## 4. Copy Environment File

```bash
cp .env.example .env
```
Update .env with your database credentials:

```bash
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_main
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Generate Application Key

```bash
php artisan key:generate
```

## 6. Run Migrations

This will create all tables in the database:
```bash
php artisan migrate
``` 

## 7. Seed Default Data

This will insert:
- Default Client (CLIENT001 + dynamic token)
- Default Admin User (admin@gmail.com / admin123)

```bash
php artisan db:seed --class=DatabaseSeeder
``` 

## 8. Start Laravel Server
```bash
php artisan serve
``` 
-Default URL: http://127.0.0.1:8000

## 9. Test Endpoints

- Clients API: GET /api/clients
- Users API: GET /api/users
- Use Postman or React frontend to test