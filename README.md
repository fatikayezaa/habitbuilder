# HabitBuilder

<p align="center">
  <img src="screenshots/dashboard-home.png" alt="HabitBuilder Dashboard" width="95%">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white" alt="Docker">
  <img src="https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
</p>

<p align="center">
A modern Habit Tracking Web Application built with Laravel and Docker.
</p>

---

## 📖 About

HabitBuilder is a web-based habit tracking application that helps users build positive habits through daily activity tracking, progress monitoring, analytics, and achievements.

Developed with **Laravel**, **Docker**, and **MySQL**, the application provides a consistent development environment while implementing CRUD operations, authentication, scheduling, and analytical dashboards.

This project was developed as part of the **DevOps and Agile Development** course at **Universitas Nasional**.

---

## ✨ Features

- 🔐 User Authentication
- 🏠 Dashboard Overview
- 📁 Category Management
- ✅ Habit Management
- 📅 Habit Scheduling
- ✔️ Daily Habit Check-in
- 📊 Analytics Dashboard
- 🏆 Achievement System
- 👤 User Profile
- ⚙️ Settings

---

## 🛠 Tech Stack

| Category | Technology |
|-----------|------------|
| Backend | Laravel 13 |
| Language | PHP 8.4 |
| Frontend | Blade & Tailwind CSS |
| Database | MySQL 8 |
| Containerization | Docker & Docker Compose |
| Web Server | Nginx |
| Icons | Bootstrap Icons |
| Version Control | Git & GitHub |

---

# 📸 Application Preview

## Authentication

| Login | Register |
|-------|----------|
| ![](screenshots/login.png) | ![](screenshots/register.png) |

---

## Dashboard

| Dashboard | Statistics & Achievements |
|-----------|---------------------------|
| ![](screenshots/dashboard-home.png) | ![](screenshots/dashboard-statistics.png) |

---

## Categories

| Category List | Create Category |
|--------------|-----------------|
| ![](screenshots/categories-list.png) | ![](screenshots/categories-create.png) |

---

## Habits

| Habit List | Create Habit |
|-----------|--------------|
| ![](screenshots/habits-list.png) | ![](screenshots/habits-create.png) |

---

## Analytics

| Weekly Analytics | Monthly Analytics |
|-----------------|-------------------|
| ![](screenshots/analytics-weekly.png) | ![](screenshots/analytics-monthly.png) |

---

## Profile & Settings

| Profile | Settings |
|---------|----------|
| ![](screenshots/profile.png) | ![](screenshots/settings.png) |

---

## 📂 Project Structure

```text
src/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── screenshots/
├── .env.example
├── artisan
├── composer.json
└── README.md
```

---

# 🚀 Installation

Clone the repository

```bash
git clone https://github.com/fatikayezaa/habitbuilder.git
```

Move into the project directory

```bash
cd habitbuilder
```

Install dependencies

```bash
composer install
```

Copy the environment file

```bash
cp .env.example .env
```

Generate the application key

```bash
php artisan key:generate
```

Run Docker

```bash
docker compose up -d
```

Run database migrations

```bash
php artisan migrate
```

(Optional) Seed the database

```bash
php artisan db:seed
```

---

## 📌 Main Modules

- Authentication
- Dashboard
- Categories
- Habits
- Habit Logs
- Habit Schedules
- Analytics
- Achievements
- Profile
- Settings

---

## 👩‍💻 Author

**Fatika Dwi Maiyeza**

Information Systems Student  
Universitas Nasional

GitHub: **https://github.com/fatikayezaa**

---

## 📄 License

This project was developed for educational and academic purposes.