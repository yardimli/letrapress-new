# LetraPress

LetraPress is a focused media-outreach workspace built with Laravel 10, Laravel Breeze, plain Blade templates, Tailwind CSS, and browser-native AJAX.

## Workspace

- Journalist directory
- Outlet directory
- Contact lists
- Press releases
- Public newsroom stories
- Standard Breeze account management

The interface intentionally avoids Blade component tags (`<x-…>`). Light and dark themes are stored in the browser.

## Local setup

```bash
composer install
npm install
php artisan migrate
npm run build
php artisan serve
```

The local `.env` uses the same database connection as the original LetraPress project. Automated tests are isolated in an in-memory SQLite database.

## Verification

```bash
php artisan test
npm run build
```
