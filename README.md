# BD Growth Suite — Laravel

Production Laravel application for [bdgrowthsuite.com](https://bdgrowthsuite.com): public marketing site, CMS-backed content, member dashboard, admin panel, inquiry handling, email automation, and API endpoints for reviews sync and Zoom clinic registration.

**Repository:** [github.com/BusinessLabsHQ/bd-growth-suite-laravel](https://github.com/BusinessLabsHQ/bd-growth-suite-laravel)

| Branch    | Environment | URL                              |
|-----------|-------------|----------------------------------|
| `main`    | Production  | https://bdgrowthsuite.com        |
| `staging` | Staging     | https://staging.bdgrowthsuite.com |

---

## Stack

| Layer        | Technology                          |
|--------------|-------------------------------------|
| Framework    | Laravel 12 (PHP 8.2+)               |
| Database     | MySQL 8+                            |
| Frontend     | Blade templates, static CSS in `public/css/` |
| Auth UI      | Laravel Breeze + Vite/Tailwind (`resources/`) |
| Queue/Cache  | Database driver                     |
| Mail         | SMTP (cPanel mail or transactional provider) |

---

## Features

### Public site
- Homepage, Services, Customization, BLabs Review, Webinars, Zoom Clinics
- Legal pages (Privacy, Terms, License)
- CMS-driven **Blog**, **Solutions**, and **Tools** (`/blog`, `/solutions`, `/tools`)
- Inquiry modal + form submission with rate limiting
- Markdown mirrors for AI crawlers (`.md` routes)
- SEO: structured data, sitemap, `llms.txt`, redirects middleware

### Authentication & dashboard
- Sign up / login at `/signup` and `/login`
- User dashboard at `/dashboard` (profile, password, photo, notifications)
- Role-based access: `user`, `admin`

### Admin (`/admin`, admin role required)
- Posts (blog, solutions, tools), categories, media library
- Email templates, inquiries, website settings
- Zoom clinics, URL redirects, activity log

### API (`/api/*`)
| Method | Endpoint                  | Auth                    |
|--------|---------------------------|-------------------------|
| GET    | `/api/reviews/list`       | Public                  |
| GET    | `/api/reviews/count`      | Public                  |
| POST   | `/api/reviews/sync`       | `REVIEWS_SYNC_TOKEN`    |
| POST   | `/api/zoom-clinics/register` | Public (throttled)   |
| GET    | `/api/inquiry`            | Public (spec)           |
| POST   | `/api/inquiry/submit`     | `INQUIRY_AGENT_TOKEN`   |

### Console commands
| Command | Purpose |
|---------|---------|
| `php artisan email:process-outbox` | Send queued emails from outbox |
| `php artisan bdgs:optimize-production` | Cache config, routes, views, events |
| `php artisan bdgs:clear-caches` | Clear all optimization caches |
| `php artisan migrate` | Run database migrations |

---

## Local development

### Requirements
- PHP 8.2+ with extensions: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`
- Composer 2.x
- Node.js 20+ and npm (for Breeze/Vite auth pages)
- MySQL 8+

### Setup

```bash
git clone https://github.com/charanBlabs/bdgs-laravel.git
cd bdgs-laravel
git checkout staging   # or main for production parity

cp .env.example .env
composer install
php artisan key:generate

# Create MySQL database, then set DB_* in .env
php artisan migrate
php artisan db:seed

npm install
npm run build

php artisan serve
```

Visit http://localhost:8000

**Default admin (after seeding):** credentials from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` (defaults in `.env.example` — change immediately).

### Dev workflow with queues

```bash
composer dev
```

Runs `artisan serve`, queue listener, log tail, and Vite dev server concurrently.

---

## Environment variables

Copy `.env.example` to `.env` on each server. Never commit `.env`.

For **staging**, use the dedicated template:

```bash
cp .env.staging.example .env
php artisan key:generate
```

When `APP_ENV=staging`, `/robots.txt` automatically returns `Disallow: /` so search engines do not index the staging site. Production serves the full allow/disallow rules with a dynamic sitemap URL from `APP_URL`.

| Variable | Production | Staging |
|----------|------------|---------|
| `APP_ENV` | `production` | `staging` |
| `APP_DEBUG` | `false` | `true` (optional) |
| `APP_URL` | `https://bdgrowthsuite.com` | `https://staging.bdgrowthsuite.com` |
| `DB_DATABASE` | `bdgs_prod` (example) | `bdgs_staging` |
| `REVIEWS_SYNC_TOKEN` | Strong random string | Different token |
| `INQUIRY_AGENT_TOKEN` | Strong random string | Different token |
| `MAIL_*` | Production SMTP | Staging mailbox or Mailtrap |
| `GOOGLE_ANALYTICS_ID` | GA4 `G-…` measurement ID | Leave empty (or test IDs + `TRACKING_ENABLED=true`) |
| `FACEBOOK_PIXEL_ID` | Meta Pixel ID | Leave empty |
| `LINKEDIN_PARTNER_ID` | LinkedIn Insight Tag partner ID | Leave empty |
| `LINKEDIN_ZOOM_CLINIC_CONVERSION_ID` | LinkedIn conversion ID for Zoom signup | Leave empty |

Generate `APP_KEY` once per environment:

```bash
php artisan key:generate
```

### Marketing pixels (POST-PRODUCTION)

Zoom Clinic registration fires **GA4** `zoom_clinic_signup`, **Meta** `Lead`, and **LinkedIn** conversion **on successful form submit** (in-modal — no thank-you page).

Code is ready; **IDs must be set in production `.env`** or nothing loads. Full steps and verification: **[docs/post-production-checklist.md](docs/post-production-checklist.md)**.

---

## Deployment architecture (recommended)

Use **two separate cPanel Git deployments** — one per branch — with **separate databases** and **separate `.env` files**. This is the safest pattern for Laravel on shared hosting.

```
GitHub main    ──► cPanel Git (prod)    ──► ~/bdgs-laravel/public        ──► bdgrowthsuite.com
GitHub staging ──► cPanel Git (staging) ──► ~/bdgs-laravel-staging/public ──► staging.bdgrowthsuite.com
```

### Why `staging.bdgrowthsuite.com`?
- Test migrations, CMS changes, and email flows before production
- Isolated database — staging seed data never touches live inquiries or users
- Same codebase, different config via `.env`
- Recommended: add `robots.txt` disallow or HTTP auth on staging (see below)

---

## cPanel deployment — production (`main`)

### 1. Create subdomain / document root

In cPanel **Domains**:
- Primary domain `bdgrowthsuite.com` document root → `/home/USERNAME/bdgs-laravel/public`

> Laravel must serve from the `public/` folder, not the project root.

### 2. Create MySQL database

cPanel → **MySQL Databases**:
- Database: e.g. `username_bdgs_prod`
- User with full privileges
- Note host (usually `localhost`)

### 3. Clone via Git Version Control

cPanel → **Git Version Control** → **Create**:
- Clone URL: `https://github.com/charanBlabs/bdgs-laravel.git`
- Repository path: `/home/USERNAME/bdgs-laravel`
- Track branch: **`main`**

### 4. Configure `.env` (one time, on server)

SSH or cPanel File Manager:

```bash
cd ~/bdgs-laravel
cp .env.example .env
nano .env   # set production values
php artisan key:generate
```

Minimum production `.env`:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://bdgrowthsuite.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=username_bdgs_prod
DB_USERNAME=username_bdgs_user
DB_PASSWORD=your-secure-password

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=mail.bdgrowthsuite.test
MAIL_PORT=4443
MAIL_ENCRYPTION=ssl
MAIL_USERNAME=noreply@bdgrowthsuite.test
MAIL_PASSWORD=your-mail-password
MAIL_FROM_ADDRESS=noreply@bdgrowthsuite.test
MAIL_FROM_NAME="BD Growth Suite"

REVIEWS_SYNC_TOKEN=generate-a-long-random-string
INQUIRY_AGENT_TOKEN=generate-a-long-random-string

# POST-PRODUCTION — marketing pixels (see docs/post-production-checklist.md)
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
FACEBOOK_PIXEL_ID=
LINKEDIN_PARTNER_ID=
LINKEDIN_ZOOM_CLINIC_CONVERSION_ID=
```

### 5. First deploy

Enable **Pull and Deploy** in cPanel Git, or run manually:

```bash
cd ~/bdgs-laravel
bash scripts/cpanel-deploy.sh
php artisan db:seed   # first deploy only
```

The repo includes `.cpanel.yml` so future **Pull and Deploy** runs:
1. `composer install --no-dev`
2. `npm ci && npm run build` (if npm available)
3. `php artisan migrate --force`
4. `php artisan bdgs:optimize-production`

### 6. Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

Ensure the cPanel PHP user owns `storage/` and `bootstrap/cache/`.

### 7. Cron jobs (cPanel → Cron Jobs)

```cron
* * * * * cd /home/USERNAME/bdgs-laravel && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
*/5 * * * * cd /home/USERNAME/bdgs-laravel && /usr/local/bin/php artisan email:process-outbox >> /dev/null 2>&1
```

Adjust PHP path with `which php` over SSH if needed.

### 8. Post-deploy checklist

- [ ] https://bdgrowthsuite.com/up returns `{"status":"ok"}`
- [ ] Login and admin panel work
- [ ] Inquiry form submits
- [ ] Mail sends (test from admin email template)
- [ ] `public/storage` symlink exists (`php artisan storage:link`)

---

## cPanel deployment — staging (`staging`)

Repeat the production steps with these differences:

| Setting | Value |
|---------|-------|
| Subdomain | `staging.bdgrowthsuite.com` |
| Repository path | `/home/USERNAME/bdgs-laravel-staging` |
| Document root | `/home/USERNAME/bdgs-laravel-staging/public` |
| Git branch | **`staging`** |
| Database | Separate DB, e.g. `username_bdgs_staging` |
| `APP_URL` | `https://staging.bdgrowthsuite.com` |
| `APP_DEBUG` | `true` (optional, for debugging) |

### Staging `.env` setup

```bash
cd ~/bdgs-laravel-staging
cp .env.staging.example .env
nano .env   # set staging DB, mail, tokens
php artisan key:generate
```

### Staging hardening

**Search engines (automatic):** With `APP_ENV=staging`, `/robots.txt` returns `Disallow: /` for all crawlers. No manual file edit needed.

**Optional — HTTP Basic Auth** — in staging `public/.htaccess` (before Laravel rules):

```apache
AuthType Basic
AuthName "Staging"
AuthUserFile /home/USERNAME/.htpasswd-staging
Require valid-user
```

Create password file via cPanel or `htpasswd`.

### Staging workflow

```bash
# Develop on staging branch locally
git checkout staging
git pull origin staging
# ... make changes ...
git add .
git commit -m "Describe change"
git push origin staging
# cPanel Pull and Deploy on staging repo

# When ready for production
git checkout main
git merge staging
git push origin main
# cPanel Pull and Deploy on production repo
```

---

## Directory structure (key paths)

```
app/
  Http/Controllers/   # Public, Admin, Dashboard, API, Auth
  Models/             # Bdgs* Eloquent models
  Services/           # Email, Inquiry, Media, Reviews, Settings
database/
  migrations/         # Schema (bdgs_* tables)
  seeders/            # Roles, admin user, solutions, email templates
public/
  css/                # Page-specific stylesheets (primary frontend assets)
  js/                 # Page-specific scripts
  index.php           # Front controller (document root entry)
resources/views/
  layouts/bdgs.blade.php   # Public site shell
  pages/              # Marketing pages
  admin/              # Admin CMS views
  dashboard/          # Member dashboard views
routes/
  web.php             # Web routes
  api.php             # API routes
  auth.php            # Authentication routes
content/md/           # Markdown mirrors for public pages
scripts/
  cpanel-deploy.sh    # Post-deploy script for cPanel Git
```

---

## Database

### Migrations

```bash
php artisan migrate          # local / staging
php artisan migrate --force  # production (non-interactive)
```

### Seeders (initial setup only)

```bash
php artisan db:seed
```

Seeds: roles, admin user, content types, solution categories/data, email templates, website settings, zoom clinics.

> Do **not** run `db:seed` on production after go-live unless you intend to reset/merge seed data.

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| 500 error after deploy | Check `storage/logs/laravel.log`; verify `.env` and `APP_KEY` |
| CSS/JS missing on auth pages | Run `npm ci && npm run build` on server |
| Uploaded images 404 | Run `php artisan storage:link` |
| Routes not updating | Run `php artisan bdgs:clear-caches` then `bdgs:optimize-production` |
| Permission denied on logs | `chmod -R 775 storage bootstrap/cache` |
| Wrong site URL in emails/links | Set correct `APP_URL` in `.env`, then re-cache config |

Clear all caches:

```bash
php artisan bdgs:clear-caches
```

---

## Security notes

- Keep `APP_DEBUG=false` on production
- Use strong, unique values for `REVIEWS_SYNC_TOKEN` and `INQUIRY_AGENT_TOKEN`
- Change default `ADMIN_PASSWORD` before first production seed
- Never commit `.env`, `.env.test-token`, or IDE config (`.cursor/`)
- Run `composer install --no-dev` on production servers

---

## Hosting & server selection

For measured project sizes, server tier comparison, and a pre-purchase checklist (including future payment gateways and APIs), see **[docs/hosting-server-selection.md](docs/hosting-server-selection.md)**.

### Must-do after go-live

See **[docs/post-production-checklist.md](docs/post-production-checklist.md)** — especially **marketing pixel IDs** (`GOOGLE_ANALYTICS_ID`, `FACEBOOK_PIXEL_ID`, `LINKEDIN_PARTNER_ID`, `LINKEDIN_ZOOM_CLINIC_CONVERSION_ID`) so Zoom Clinic signup conversions report correctly.

---

## License

MIT — see [LICENSE](LICENSE) if present. Proprietary content and branding © BD Growth Suite / Business Labs.
