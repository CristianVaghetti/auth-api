# 🚀 Project – Local Development Environment

This repository provides everything you need to spin up a **local development environment** using **Docker + Docker Compose**, with support for **Laravel**, **Redis**, **PostgreSQL**, and **Nginx with self‑signed SSL**.

---

## 🐳 Running the Environment with Docker

To build and start all containers, run:

```bash
docker compose -f .docker/compose/docker-compose.yml --project-directory . up -d
```

This will:

* Build all required images
* Start every service in detached mode (`-d`)
* Set up the full project environment

---

## 🔐 SSL Certificates (Local Environment Only)

To generate **self‑signed SSL certificates** for Nginx in your local environment:

```bash
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout .docker/nginx/certs/nginx-selfsigned.key \
  -out .docker/nginx/certs/nginx-selfsigned.crt \
  -subj "/C=BR/ST=SP/L=SaoPaulo/O=Dev/CN=localhost"
```

⚠️ **Warning:** These certificates are intended **only for local development**. **Do not use them in production.**

---

## 🧠 Background Processing

This project comes **preconfigured out of the box** with Laravel background workers:

* **Laravel Horizon** is already installed and ready to monitor Redis queues
* **Laravel Scheduler** runs automatically in the background
* **Supervisor** is configured to start at container startup and execute schedule:work and horizon, with no manual intervention required

No extra setup is required — once the containers are up, queues and scheduled tasks are already running 🚀

---

## ⚡ Artisan Shortcut

You can run Artisan commands without typing `php` at the beginning:

```bash
php artisan tinker
artisan tinker
```

This makes day‑to‑day development faster and cleaner 🚀

---

## 🧪 Required Environment Variables

Make sure the following variables are set in your `.env` file:

### 🔴 Redis

```env
REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=password
REDIS_PORT=6379

CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

### 🐘 PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=database
DB_PORT=5432
DB_DATABASE=database
DB_USERNAME=username
DB_PASSWORD=password
```

---

## ✅ All Set!

Your local environment should now be up and running, ready for development.

If anything goes wrong:

* Make sure Docker is running 🐳
* Check if required ports are already in use
* Run `docker compose ps` to inspect container status

Happy coding! 💻🔥
