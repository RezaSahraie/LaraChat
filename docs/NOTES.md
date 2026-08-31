# LaraChat — Backend Learning Notes

## 01. Project Setup & Infrastructure

### Project

LaraChat is a real-world chat application built primarily for learning backend development with Laravel.

The backend is the main learning focus. The frontend will be built separately with React, Inertia, and Tailwind CSS.

### Current Stack

* Laravel 13.29.0
* PHP 8.5.9 inside Docker
* MySQL 8.4
* Redis
* Docker Desktop
* Docker Compose
* Composer 2.10.2
* Node.js 24
* npm 11
* Git

---

## Docker

### Services

The project currently has three Docker services:

```text
laravel.test
mysql
redis
```

### Architecture

```text
Laravel Container
       │
       ├──────────────► MySQL Container
       │                  mysql:3306
       │
       └──────────────► Redis Container
                          redis:6379
```

Services communicate through the Docker Compose network.

Therefore Laravel uses:

```env
DB_HOST=mysql
REDIS_HOST=redis
```

instead of `localhost`.

### Important Concepts

#### Image

A Docker image is a blueprint used to create containers.

#### Container

A container is a running instance of an image.

#### Network

Docker Compose creates a private network where services can communicate using their service names.

For example:

```text
Laravel → mysql:3306
Laravel → redis:6379
```

---

## MySQL

The initial Laravel project was created with SQLite.

After configuring Docker, MySQL became the application's database:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
```

The initial migrations were then executed against MySQL.

### Migration Status

```text
create_users_table   [1] Ran
create_cache_table   [1] Ran
create_jobs_table    [1] Ran
```

### Important Lesson

Migration status belongs to the database being used.

The migrations that originally ran on SQLite did not mean that the new MySQL database had those tables.

Running:

```bash
php artisan migrate
```

created the migration table and executed the migrations against MySQL.

---

## Redis

Redis connectivity was tested from inside the Laravel container using Tinker.

Test:

```php
use Illuminate\Support\Facades\Redis;

Redis::set('larachat:test', 'hello');

Redis::get('larachat:test');
```

Result:

```text
"hello"
```

This confirms that:

```text
Laravel → Redis
```

is working correctly.

### Why Redis?

Redis will potentially be used later for:

* Cache
* Queue-related infrastructure
* Rate limiting
* Realtime/broadcasting infrastructure

We should only introduce each use case when it has a real architectural reason.

---

## Commands Learned

### Validate Compose configuration

```bash
docker compose config
```

### Build images

```bash
docker compose build
```

### Start containers

```bash
docker compose up -d
```

### Check container status

```bash
docker compose ps
```

### Execute a Laravel command inside the container

```bash
docker compose exec laravel.test php artisan about
```

### Check migrations

```bash
docker compose exec laravel.test php artisan migrate:status
```

### Run migrations

```bash
docker compose exec laravel.test php artisan migrate
```

### Open Laravel Tinker

```bash
docker compose exec laravel.test php artisan tinker
```

---

## Problems & Solutions

### Sail could not start from PowerShell

Running:

```bash
./vendor/bin/sail up -d
```

resulted in:

```text
WSL ERROR: execvpe(/bin/bash) failed: No such file or directory
```

Docker itself was working correctly.

Instead of depending on the Sail wrapper, Docker Compose was used directly:

```bash
docker compose up -d
```

This successfully started all services.

### Missing WWWUSER / WWWGROUP

Docker Compose initially reported:

```text
The "WWWGROUP" variable is not set.
The "WWWUSER" variable is not set.
```

The environment was configured with:

```env
WWWUSER=1000
WWWGROUP=1000
APP_PORT=8000
```

After that:

```bash
docker compose config
```

completed without warnings.

---

## Current Status

Infrastructure is working:

```text
Laravel       ✅
PHP           ✅
MySQL         ✅
Redis         ✅
Docker        ✅
Migrations    ✅
Redis test    ✅
```

The next major phase is installing and configuring the frontend stack:

```text
Inertia
React
Tailwind CSS
Vite
```

After that we will begin designing the actual chat domain and backend architecture.
