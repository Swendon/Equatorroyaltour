# Equator Royal Tour CBO — Website

A PHP + MySQL website for Equator Royal Tour Community-Based Organization,
built to run on XAMPP (Apache + MySQL + PHP).

## Languages

### Frontend

- **HTML** — Page structure (embedded in PHP files)
- **CSS** — Styling (`css/style.css`)
- **JavaScript** — Client-side interactivity (`js/main.js`)

### Backend

- **PHP** — Server-side logic and templating
- **SQL** — Database schema and seed data (`database.sql`)

### Config

- **JSON** — VS Code settings (`.vscode/settings.json`)

## What's included

- Public site: Home, About Us, The Project, Trader Registration, Contact
- MySQL-backed trader registration form and contact form
- A simple admin panel to review registrations and messages (`/admin`)
- CSRF protection and honeypot spam prevention on all public forms
- Encrypted storage of National ID numbers in the database
- Email notifications for new registrations and contact messages
- Site-wide floating WhatsApp contact button
- Heritage gallery and trader testimonials on the homepage
- Downloadable project proposal PDF link

## Setup instructions (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) if you don't have it yet.
2. Copy the `equator-royal-tour` folder into your XAMPP `htdocs` directory:
   - Windows: `C:\xampp\htdocs\equator-royal-tour`
   - macOS: `/Applications/XAMPP/htdocs/equator-royal-tour`
   - Linux: `/opt/lampp/htdocs/equator-royal-tour`
3. Open `http://localhost/phpmyadmin` in your browser.
4. Click **Import**, choose the `database.sql` file from this project, and click **Go**.
   This creates the `equator_royal_tour` database with all required tables and
   seed data (trading centres).
5. Visit `http://localhost/equator-royal-tour/` in your browser. The site should load.

## First-run admin setup

The database no longer ships with a default admin account. On first visit to
`/admin/login.php`, you will see a **Create Administrator** form. Use it to
create your first admin account. After that, log in normally.

## Running the project

### Frontend (Apache + PHP)

```powershell
C:\xampp\xampp.exe start apache
```

### Backend (MySQL)

```powershell
C:\xampp\xampp.exe start mysql
```

### Start both services

```powershell
C:\xampp\xampp.exe start
```

### Stop services

```powershell
C:\xampp\xampp.exe stop
```

## Database configuration

Connection settings are in `config.php`. The defaults match a fresh XAMPP install:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'equator_royal_tour');
```

### Security notes

- **Change `DB_PASS`** from `''` (empty) to a strong password before any production deployment.
- **Change `APP_KEY`** in `config.php` to a random 32-byte value. This key is used to encrypt National ID numbers at rest.
- **Email notifications** use PHP's `mail()` function by default. For reliable delivery, configure an SMTP library such as PHPMailer and update the notification calls in `register.php` and `contact.php`.
- **CSRF tokens** are required on all admin and public form submissions.
- **Honeypot fields** are included on public forms to reduce spam.

## Admin panel

Visit `http://localhost/equator-royal-tour/admin/login.php`.

- Create your first admin account via the setup form.
- From the dashboard you can view all trader registrations and contact messages,
  and update a registration's status (Pending / Approved / Rejected).
- Admins are required to change their password on first login if created via the legacy setup flow.

## Folder structure

```
equator-royal-tour/
├── admin/                  # Admin login + dashboard (protected by session)
│   ├── login.php
│   ├── change-password.php
│   ├── dashboard.php
│   ├── auth_check.php
│   └── logout.php
├── css/style.css           # Site styling
├── js/main.js              # Mobile nav toggle
├── includes/               # Shared partials
│   ├── header.php
│   ├── footer.php
│   └── banking-card.php
├── config.php              # Database connection + encryption helpers
├── database.sql            # Schema + seed data (import this first)
├── assets/                 # Uploaded proposal PDFs and images
├── index.php               # Redirects to home.php
├── home.php                # Homepage with gallery, testimonials, and proposal download
├── about.php               # About the organization
├── project.php             # Project objectives, phases, budget
├── register.php            # Trader registration form (writes to MySQL)
└── contact.php             # Contact / partnership enquiry form
```

## Notes

- All form inputs are sanitized with `htmlspecialchars()` on output and
  prepared statements (`mysqli_prepare`) on database writes to prevent SQL
  injection and XSS.
- Trading centres are loaded from the `trading_centres` database table, so
  adding a new centre in phpMyAdmin automatically updates all dropdowns and
  lists across the site.
- The site uses a floating WhatsApp button on every page for instant communication.
- This project is based on the Equator Royal Tour CBO project proposal
  ("Mumberes Safe Trade Corridor, Railway and Catchment Protection Project").
