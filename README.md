# TrackZen

TrackZen is a habit tracking backend and web starter built with Laravel 12, Livewire 4, Sanctum, and Fortify. It provides authenticated habit management, daily completion logs, and simple streak-based statistics for personal productivity workflows.

## Features

- User registration, login, logout, and profile retrieval
- Token-based API authentication with Laravel Sanctum
- Habit CRUD endpoints
- Daily habit log history with duplicate-per-day protection
- Per-habit statistics including current streak, longest streak, and completion rate
- Overview statistics across active habits
- Laravel + Livewire web shell for dashboard-based expansion

## Stack

- PHP 8.2+
- Laravel 12
- Livewire 4
- Laravel Fortify
- Laravel Sanctum
- Vite + Tailwind CSS 4
- Pest for testing

## Getting Started

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Update the database settings in `.env`, then run:

```bash
php artisan migrate
```

### 3. Run locally

```bash
composer run dev
```

This starts the Laravel server, queue listener, log tailing, and Vite dev server.

## API Overview

Base path: `/api`

### Auth

- `POST /register`
- `POST /login`
- `POST /logout`
- `GET /me`

### Habits

- `GET /habits`
- `POST /habits`
- `GET /habits/{id}`
- `PUT /habits/{id}`
- `DELETE /habits/{id}`

### Habit Logs

- `GET /habits/{id}/logs`
- `POST /habits/{id}/logs`
- `DELETE /habits/{id}/logs/{logId}`

### Stats

- `GET /habits/{id}/stats`
- `GET /stats/overview`

## Example Request

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

Use the returned bearer token for protected endpoints:

```bash
curl http://127.0.0.1:8000/api/habits \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Project Notes

- The repository currently focuses on the backend/API domain for habit tracking.
- API responses and validation messages are currently written in French.
- The default web layer is minimal and can be expanded into a full dashboard UI.

## Suggested GitHub Metadata

Description:
`Habit tracking API and dashboard starter built with Laravel, Livewire, and Sanctum.`

Topics:
`laravel`, `livewire`, `sanctum`, `habit-tracker`, `productivity`, `api`, `php`, `vite`

