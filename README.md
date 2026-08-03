# Equator Royal Tour CBO — Website

A PHP + MySQL website for Equator Royal Tour Community-Based Organization,
built to run on XAMPP (Apache + MySQL + PHP).

## What's included

- Public site: Home, About Us, The Project, Trader Registration, Contact
- MySQL-backed trader registration form and contact form
- A simple admin panel to review registrations and messages (`/admin`)

## Setup instructions (XAMPP)

1. Install [XAMPP](https://www.apachefriends.org/) if you don't have it yet, and start
   **Apache** and **MySQL** from the XAMPP Control Panel.
2. Copy the `equator-royal-tour` folder into your XAMPP `htdocs` directory:
   - Windows: `C:\xampp\htdocs\equator-royal-tour`
   - macOS: `/Applications/XAMPP/htdocs/equator-royal-tour`
   - Linux: `/opt/lampp/htdocs/equator-royal-tour`
3. Open `http://localhost/phpmyadmin` in your browser.
4. Click **Import**, choose the `database.sql` file from this project, and click **Go**.
   This creates the `equator_royal_tour` database with all required tables and
   seed data (trading centres + a default admin account).
5. Visit `http://localhost/equator-royal-tour/` in your browser. The site should load.

## Database configuration

Connection settings are in `config.php`. The defaults match a fresh XAMPP install:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'equator_royal_tour');
```

If your MySQL root user has a password, or you used a different database name
during import, update these values accordingly.

## Admin panel

Visit `http://localhost/equator-royal-tour/admin/login.php`.

- **Username:** `admin`
- **Password:** `admin123`

From the dashboard you can view all trader registrations and contact messages,
and update a registration's status (Pending / Approved / Rejected).

**Change the default password after first login** by generating a new hash and
updating the `admins` table:

```php
<?php
echo password_hash('your-new-password', PASSWORD_DEFAULT);
```

Run that snippet with PHP (e.g. save it as a temporary `.php` file in `htdocs`
and open it in the browser), copy the resulting hash, then update it in
phpMyAdmin under `admins` → `password_hash`.

## Folder structure

```
equator-royal-tour/
├── admin/              # Admin login + dashboard (protected by session)
├── css/style.css        # Site styling
├── js/main.js            # Mobile nav toggle
├── includes/            # Shared header/footer
├── config.php            # Database connection settings
├── database.sql          # Schema + seed data (import this first)
├── index.php              # Home page
├── about.php              # About the organization
├── project.php            # Project objectives, phases, budget
├── register.php           # Trader registration form (writes to MySQL)
└── contact.php            # Contact / partnership enquiry form
```

## Notes

- All form inputs are sanitized with `htmlspecialchars()` on output and
  prepared statements (`mysqli_prepare`) on database writes to prevent SQL
  injection and XSS.
- This project is based on the Equator Royal Tour CBO project proposal
  ("Mumberes Safe Trade Corridor, Railway and Catchment Protection Project").
