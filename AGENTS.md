# AGENTS.md — EduPortal

Laravel 12 school management system with 3 role-based portals: `admin`, `guru` (teacher), `siswa` (student).

## Quick start

| Command | What it does |
|---|---|
| `composer setup` | Full bootstrap: install deps, create .env, key:generate, migrate, npm install & build |
| `composer dev` | Runs `php artisan serve` + `php artisan queue:listen --tries=1` + `npm run dev` concurrently |
| `composer test` | Runs `config:clear` then `php artisan test` (Pest) |
| `php artisan test --filter=SomeTest` | Run a single test file |

## Architecture

- **Auth**: Laravel Breeze. Role-based redirect after login via `user->role` (admin/guru/siswa). `CheckRole` middleware (`app/Http/Middleware/CheckRole.php`) gates admin/guru/siswa route groups.
- **Routes**: `routes/web.php` — three route groups under prefixes `/admin`, `/portal-guru`, `/portal-siswa`, each with `auth` + `role:X` middleware. Plus `/ujian` (auth only), `/api/v1/*` (auth required, role-filtered), Auth routes in `routes/auth.php`.
- **DB**: SQLite (`database/database.sqlite`). Queue, cache, session all use `database` driver. Notifications use `database` channel (stored in `notifications` table).
- **Queue**: Required for notification delivery. `composer dev` includes `queue:listen` automatically.
- **Frontend**: Tailwind CSS 3 + Alpine.js 3 + Blade. Custom design tokens in `tailwind.config.js` (primary `#000421`, secondary `#A6600C`, tertiary `#FEAF2C`; Work Sans headings, Source Sans 3 body).
- **API**: `routes/api.php` — prefix `/api/v1`, authenticated (session-based) read-only endpoints for siswa/nilai/jadwal. Data filtered by user role: admin sees all, guru sees relevant, siswa sees own.
- **Authorization**: `CheckRole` middleware gates routes by role. Model Policies in `app/Policies/` enforce ownership: `TugasPolicy`, `SoalPolicy`, `UjianPolicy` ensure guru can only manage their own resources.

## Testing

- **Pest PHP 3** (`pestphp/pest ^3.8` + `pestphp/pest-plugin-laravel ^3.2`)
- Feature tests use `RefreshDatabase` trait with SQLite `:memory:` (defined in `tests/Pest.php`)
- Test files in `tests/Feature/` (auth, admin access, API, role middleware, profile) and `tests/Unit/`
- `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync` in phpunit.xml

## Agent config

`boost.json` enables these skills (loaded automatically by OpenCode):
- `laravel-best-practices` — .agents/skills/laravel-best-practices/ (20 rule files)
- `pest-testing` — .agents/skills/pest-testing/
- `tailwindcss-development` — .agents/skills/tailwindcss-development/

## Developer conventions

- Linting: Laravel Pint (`./vendor/bin/pint`) but no explicit `composer pint` script.
- No CI workflows configured.
- `.env` uses `APP_DEBUG=true` by default (local dev).
- Custom Blade components in `resources/views/components/`.
- Every model has a corresponding migration and factory (5 factories exist for core models).
- `Database\Seeders\DatabaseSeeder` creates a full demo dataset (admin, 3 teachers, 5 students, mapel, jadwal, soal, ujian, nilai, spp, etc.).
- Model policies auto-discovered by convention (`{Model}Policy` in `app/Policies/`).
- Form Request validation classes in `app/Http/Requests/Admin/` for admin CRUD (Siswa, Guru, Soal, Ujian).
- Notifications (`TugasBaruNotification`, `PengumumanNotification`) use `database` channel and are dispatched automatically on create.
- Scheduled tasks in `routes/console.php`: auto-expire ujian (every minute), generate SPP bulanan (monthly), session cleanup (daily).
- Admin panel: TracerStudy + KontakMessage management at `/admin/tracer-study` and `/admin/kontak`.
