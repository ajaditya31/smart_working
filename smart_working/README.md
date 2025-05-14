# 📅 Laravel Event Booking API

A simple RESTful API for managing events, attendees, and event bookings using Laravel.

---

## 🚀 Features

- Country-based event locations (no specific addresses)
- Booking with capacity limit
- Prevent duplicate bookings
- Full CRUD for Events
- Structured, meaningful JSON responses
- Fully tested with PHPUnit
- Auto-generated API documentation using Scribe

---

## 🛠️ Requirements

- PHP 8.1+
- Composer
- Laravel 10+
- MySQL or PostgreSQL
- Node.js (for Laravel Mix if using frontend)

---

## ⚙️ Setup Instructions

```bash
# Clone the repository
git clone https://github.com/your-username/laravel-event-booking.git
cd laravel-event-booking

# Install dependencies
composer install

# Copy environment file and configure
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure your .env (DB settings etc.)

# Run migrations
php artisan migrate

# (Optional) Seed database
php artisan db:seed

# Run tests
php artisan test
