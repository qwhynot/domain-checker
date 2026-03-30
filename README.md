# Domain Monitor

Веб-застосунок для автоматичного моніторингу доступності доменів з email-нотифікаціями при падінні та відновленні.

---

## Стек технологій

| Компонент | Версія |
|---|---|
| PHP | 8.4 |
| Laravel | 13 |
| MySQL | 8.0 |
| Redis | latest |
| Nginx | системний |
| Tailwind CSS | 4.x |
| Alpine.js | 3.x |

---

## Функціонал

- Реєстрація та авторизація користувачів (Laravel Breeze)
- CRUD для доменів — додавання, редагування, видалення, перегляд
- Налаштування для кожного домену:
  - Інтервал перевірки (1–1440 хвилин)
  - Таймаут з'єднання (1–60 секунд)
  - HTTP-метод перевірки (GET або HEAD)
  - Вмикання/вимикання моніторингу
- Автоматична перевірка доменів щохвилини через Laravel Scheduler
- Асинхронна обробка перевірок через Queue (Redis)
- Захист від дублювання — `ShouldBeUnique` на job
- Збереження повної історії перевірок (статус, HTTP-код, час відповіді, помилка)
- Uptime-статистика за 24 години та 7 днів
- Email-нотифікації при падінні домену (UP → DOWN/ERROR)
- Email-нотифікації при відновленні домену (DOWN/ERROR → UP)

---

## Архітектура

```
┌─────────────────────────────────────────────┐
│               Один Docker-контейнер          │
│                                             │
│  ┌──────────┐  ┌──────────┐                 │
│  │  Nginx   │  │ PHP-FPM  │  (веб-сервер)   │
│  └──────────┘  └──────────┘                 │
│  ┌──────────────────────────┐               │
│  │  schedule:work           │  (scheduler)  │
│  └──────────────────────────┘               │
│  ┌──────────────────────────┐               │
│  │  queue:work              │  (worker)     │
│  └──────────────────────────┘               │
│             Supervisor                       │
└─────────────────────────────────────────────┘
        │                  │
   ┌────┴────┐        ┌────┴────┐
   │  MySQL  │        │  Redis  │
   └─────────┘        └─────────┘
```

Усі процеси (nginx, php-fpm, scheduler, queue worker) запускаються всередині одного контейнера через **Supervisor**.

---

## Структура репозиторію

```
├── Dockerfile                  # Production образ (Railway)
├── docker-compose.yml          # Локальна розробка
├── railway.json                # Конфіг деплою Railway
├── docker/
│   ├── nginx/
│   │   ├── default.conf        # Nginx конфіг для локальної розробки
│   │   └── railway.conf        # Nginx конфіг для production
│   ├── php/
│   │   └── Dockerfile          # PHP образ для локальної розробки
│   ├── supervisor/
│   │   └── supervisord.conf    # Конфіг Supervisor
│   └── start.sh                # Entrypoint скрипт
└── src/                        # Laravel-застосунок
    ├── app/
    │   ├── Console/Commands/
    │   │   └── CheckDomainsCommand.php     # Artisan-команда domains:check
    │   ├── Enums/
    │   │   └── CheckMethod.php             # GET | HEAD
    │   ├── Http/Controllers/
    │   │   └── DomainController.php
    │   ├── Jobs/
    │   │   └── CheckDomainJob.php          # Фонова перевірка домену
    │   ├── Models/
    │   │   ├── Domain.php
    │   │   └── DomainCheck.php
    │   ├── Notifications/
    │   │   ├── DomainDownNotification.php
    │   │   └── DomainRecoveryNotification.php
    │   ├── Policies/
    │   │   └── DomainPolicy.php
    │   └── Services/
    │       ├── DomainService.php           # CRUD + uptime
    │       └── DomainCheckService.php      # HTTP-перевірка + нотифікації
    ├── database/migrations/
    ├── resources/views/
    │   ├── landing.blade.php
    │   └── domains/
    │       ├── index.blade.php
    │       ├── show.blade.php
    │       ├── create.blade.php
    │       └── edit.blade.php
    └── routes/
        ├── web.php
        └── console.php                     # Schedule::command('domains:check')
```

---

## Деплой на Railway

Застосунок готовий до деплою на [Railway.com](https://railway.com).

### Необхідні сервіси на Railway

- **App** — основний сервіс (цей репозиторій, білдиться через `Dockerfile`)
- **MySQL** — керований сервіс Railway
- **Redis** — керований сервіс Railway

### Змінні середовища на Railway (App сервіс)

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=<згенерувати: php artisan key:generate --show>
APP_URL=https://<your-domain>.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

REDIS_HOST=${{Redis.REDISHOST}}
REDIS_PORT=${{Redis.REDISPORT}}
REDIS_PASSWORD=${{Redis.REDISPASSWORD}}

QUEUE_CONNECTION=redis
CACHE_STORE=database
SESSION_DRIVER=database

MAIL_MAILER=log

PORT=80
```
---

## Email-нотифікації

Сповіщення надсилаються автоматично:

| Подія | Тригер |
|---|---|
| Домен недоступний | статус змінився з `up` на `down` або `error` |
| Домен відновився | статус змінився з `down`/`error` на `up` |
