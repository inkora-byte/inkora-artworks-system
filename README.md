<<<<<<< HEAD
# inkora-artworks-system
=======
<<<<<<< HEAD
# inkora-artworks-system
=======
# Inkora Artworks Management System

Internal business management system for Inkora Artworks — Laravel 12 / PHP 8.3+ / MySQL / Blade / Bootstrap 5.

**This is not the public ecommerce site.** Staff and administrators only. See the project's architecture documentation (Master Software Design Document V2, Steps 3–7, Sprint 0 Checklist) for the complete specification — this README covers local setup only.

## Requirements

- PHP 8.3+
- Composer 2.x
- MySQL 8.x
- Node 20+ / npm

## Local Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy environment config and generate the app key
cp .env.example .env
php artisan key:generate

# 3. Create the database (adjust credentials to match your local setup)
mysql -u root -p -e "CREATE DATABASE inkora_artworks CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Edit .env with your real DB credentials

# 5. Run migrations
php artisan migrate

# 6. Install and build frontend assets
npm install
npm run dev   # or `npm run build` for a production build

# 7. Serve locally
php artisan serve
```

Visit `http://localhost:8000`.

## Project Conventions

- **Timezone/locale:** `Asia/Manila`, set in `config/app.php` (not `.env` — see that file).
- **Code style:** PSR-12 via Laravel Pint — run `vendor/bin/pint` before every commit (Milestone 0.9 onward).
- **Testing:** Pest — `php artisan test` (Milestone 0.11 onward).
- **Seeding a fresh environment:** `php artisan migrate:fresh --seed` reproducibly creates roles/permissions, default Settings, and one Administrator login for local development (Milestone 0.12 onward). Never runs its development-data seeder in production (explicitly environment-guarded).
- **Branching:** one branch per sprint (`sprint-0-foundation`, `sprint-1-catalog`, ...), merged to `main` only once that sprint's Definition of Done is fully met. See the AI Development Contract, Section 10.
- **Folder structure:** Controllers/Services/Repositories are organized by business module (`app/Http/Controllers/Catalog/`, `app/Services/Production/`, etc.); Models stay flat. See Technical Architecture (Step 5), Part 3.1 for the full rationale.

## Documentation

The full specification set (Master Design Document, Database Design, Business Rules & Workflows, Technical Architecture, Implementation Roadmap, AI Development Contract, Architecture Impact Analysis, Sprint 0 Checklist) is maintained outside this repository as the project's Version 2.0 frozen specification. Implementation follows it exactly; any deviation is explicitly called out and approved before being built, never silent.

## Current Status

**Sprint 0 — Project Foundation, in progress.** See the Sprint 0 Checklist for the full milestone breakdown.
>>>>>>> 3f5db2d (Initial Laravel 12 installation)
>>>>>>> d78fb5b (Initial Laravel 12 installation)
