# TaskBoard – Team Task Manager

A full-stack task management system for teams (simplified Trello/Asana clone) built with **Laravel 11**, **Vue 3**, and **MySQL**.

## Features

- User registration & authentication (Laravel Sanctum API tokens)
- Create teams and invite members
- Create boards inside teams
- Drag‑and‑drop tasks between columns: **Ready**, **In Progress**, **Impossible**
- Edit task title, description, assignee, due date
- Mark tasks as impossible and add a reason
- Responsive SPA frontend with Vue Router & Pinia
- RESTful API with authorization policies
- Docker support (optional)

## Tech Stack

**Backend:** Laravel 11, Sanctum, MySQL, PHP 8.3+  
**Frontend:** Vue 3 (Composition API), Vue Router, Pinia, Axios, Vite  
**DevOps:** Docker, Docker Compose

## Installation

### Without Docker (Local)

```bash
# Clone the repository
git clone https://github.com/DanilBabaev123/Taskboard.git
cd Taskboard

# Install PHP dependencies
composer install

# Install JS dependencies
npm install && npm run build

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env, then run migrations
php artisan migrate

# Start the development server
php artisan serve

# In another terminal, start Vite (for hot reload)
npm run dev
