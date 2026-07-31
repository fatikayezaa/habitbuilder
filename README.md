# HabitBuilder

<p align="center">
A modern <strong>Habit Tracking Web Application</strong> built with <strong>Laravel</strong>, <strong>Docker</strong>, and <strong>MySQL</strong>.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-2496ED?logo=docker&logoColor=white" alt="Docker">
  <img src="https://img.shields.io/badge/Tailwind_CSS-v3-06B6D4?logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
</p>

<p align="center">
  <img src="screenshots/dashboard-home.png" alt="HabitBuilder Dashboard" width="85%">
</p>

---

## 📖 About

HabitBuilder is a web-based habit tracking application designed to help users build and maintain positive habits through daily tracking, scheduling, analytics, and achievement milestones.

Built with **Laravel**, **Docker**, and **MySQL**, the application provides a consistent development environment while implementing authentication, CRUD operations, habit scheduling, progress tracking, and interactive dashboards.

This project was developed as part of the **DevOps and Agile Development** course at **Universitas Nasional**.

---

## ✨ Features

- ✅ User Authentication
- ✅ Dashboard Overview
- ✅ Category Management
- ✅ Habit Management
- ✅ Habit Scheduling
- ✅ Daily Habit Check-in
- ✅ Analytics Dashboard
- ✅ Achievement System
- ✅ User Profile
- ✅ Application Settings

---

## 🛠️ Tech Stack

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

## 📸 Application Preview

### 🔐 Authentication

| Register | Login |
|----------|-------|
| ![](screenshots/register.png) | ![](screenshots/login.png) |

---

### 🏠 Dashboard

| Overview | Statistics & Achievements |
|-----------|---------------------------|
| ![](screenshots/dashboard-home.png) | ![](screenshots/dashboard-statistics.png) |

---

### 📁 Categories

| Create Category | Category List |
|-----------------|---------------|
| ![](screenshots/categories-create.png) | ![](screenshots/categories-list.png) |

---

### ✅ Habits

| Create Habit | Habit List |
|--------------|------------|
| ![](screenshots/habits-create.png) | ![](screenshots/habits-list.png) |

---

### 📊 Analytics

| Weekly Analytics | Monthly Analytics |
|-----------------|-------------------|
| ![](screenshots/analytics-weekly.png) | ![](screenshots/analytics-monthly.png) |

---

### 👤 Profile & ⚙️ Settings

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
├── screenshots/
├── storage/
├── .env.example
├── artisan
├── composer.json
└── README.md
```

---

## 🚀 Installation

Clone the repository

```bash
git clone https://github.com/fatikayezaa/habitbuilder.git
```

Move into the project directory

```bash
cd habitbuilder
```

Copy the environment file

```bash
cp .env.example .env
```

Start Docker containers

```bash
docker compose up -d
```

Install dependencies

```bash
docker compose exec app composer install
```

Generate the application key

```bash
docker compose exec app php artisan key:generate
```

Run database migrations

```bash
docker compose exec app php artisan migrate
```

(Optional) Run database seeder

```bash
docker compose exec app php artisan db:seed
```

---

## 👩‍💻 Author

**Fatika Dwi Maiyeza**

Information Systems Student  
Universitas Nasional

GitHub: **[@fatikayezaa](https://github.com/fatikayezaa)**

---

## 📄 License

This project was developed for academic and educational purposes as part of the **DevOps and Agile Development** course.