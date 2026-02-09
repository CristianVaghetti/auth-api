# 🚀 Project – Auth API

This repository is a **fully decoupled authentication API**, whose **only responsibility** is to authenticate users and issue JWT tokens.

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

⚠️ **Warning:** These certificates are intended **only for local development**.  
**Do not use them in production.**

---

## 🧠 Background Processing

This project comes **preconfigured out of the box** with Laravel background workers:

* **Laravel Horizon** is already installed and ready to monitor Redis queues
* **Laravel Scheduler** runs automatically in the background
* **Supervisor** is configured to start at container startup and execute `schedule:work` and `horizon`, with no manual intervention required

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

### 🔐 JWT

```env
JWT_PRIVATE_KEY_PATH=keys/jwt-private.pem
JWT_PUBLIC_KEY_PATH=keys/jwt-public.pem
JWT_ISSUER=auth-api
JWT_TTL=900
```

---

## 🔑 Google OAuth Configuration

This project supports authentication via **Google OAuth**, but you must configure the application in **Google Cloud Console**.

### 1️⃣ Create a Project

1. Access **Google Cloud Console**
2. Create a new project (or select an existing one)
3. Enable **Google Identity / OAuth 2.0** APIs

### 2️⃣ Configure OAuth Consent Screen

1. Go to **APIs & Services → OAuth consent screen**
2. Choose **External**
3. Fill in the required fields:
   - Application name
   - Support email
   - Developer contact email
4. Save and continue (no extra scopes required)

### 3️⃣ Create OAuth Credentials

1. Navigate to **APIs & Services → Credentials**
2. Click **Create Credentials → OAuth client ID**
3. Select **Web application**
4. Configure:

**Authorized redirect URIs**

    https://localhost/v1/auth/google/callback

⚠️ Adjust domains and callback URLs according to your environment.

### 4️⃣ Environment Variables

After creating the credentials, add them to your `.env` file:

```env
AUTH_GOOGLE_CLIENT_ID=your-client-id
AUTH_GOOGLE_CLIENT_SECRET=your-client-secret
AUTH_GOOGLE_REDIRECT_URI=https://localhost/auth/google/callback
```
Once configured, Google authentication will be fully functional 🚀

---

## ✅ All Set!

Your local environment should now be up and running, ready for development.

If anything goes wrong:

* Make sure Docker is running 🐳
* Check if required ports are already in use
* Run `docker compose ps` to inspect container status

Happy coding! 💻🔥
