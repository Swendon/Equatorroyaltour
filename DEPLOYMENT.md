# Deployment Guide — Equator Royal Tour CBO

## Prerequisites
- A free PHP + MySQL hosting account (e.g., InfinityFree, 000webhost, AwardSpace)
- FTP/SFTP access or a file manager in your hosting control panel
- MySQL database created in your hosting control panel

## Step 1 — Create MySQL Database
1. Log in to your hosting control panel (cPanel or equivalent).
2. Create a **MySQL database** (e.g., `equator_royal_tour`).
3. Create a **MySQL user** with a strong password.
4. Assign the user to the database with **All Privileges**.
5. Note down:
   - Database name
   - Username
   - Password
   - Hostname (usually `localhost`, sometimes `mysql.hostinger.com` etc.)

## Step 2 — Upload Files
1. In your hosting file manager, navigate to `public_html` or `htdocs`.
2. Upload the entire `equator-royal-tour` folder contents.
   - If you unzip the project, make sure files land directly in `public_html/` (not inside a subfolder), OR
   - Keep them in `public_html/equator-royal-tour/` and access via `yourdomain.com/equator-royal-tour/`

## Step 3 — Import Database
1. Open phpMyAdmin in your hosting control panel.
2. Select your new database.
3. Click **Import** and upload `database.sql` from this project.
4. Click **Go** to import the schema and seed data.

## Step 4 — Update config.php
1. Open `config.php` in the file manager or via FTP.
2. Update these lines with your production database credentials:

```php
define('DB_HOST', 'localhost');          // Usually 'localhost', check your host
define('DB_USER', 'your_db_username');   // Replace with your MySQL username
define('DB_PASS', 'your_db_password');   // Replace with your MySQL password
define('DB_NAME', 'equator_royal_tour'); // Replace with your database name
```

3. **Generate a new APP_KEY** (for National ID encryption):
   - Generate a random string at: https://www.random.org/strings/
   - Or run in terminal: `php -r "echo bin2hex(random_bytes(32));"`
   - Replace the placeholder in config.php:

```php
define('APP_KEY', 'paste-your-random-32-byte-key-here');
```

4. **Update the notification email address** in:
   - `register.php` — line with `@mail('info@equatorroyaltour.com', ...)`
   - `contact.php` — line with `@mail('info@equatorroyaltour.com', ...)`
   Replace with your real email address.

## Step 5 — Set Permissions
Some hosts require writable directories:
- Ensure `public_html/` or your upload directory is writable for session files (most hosts handle this automatically).

## Step 6 — Test the Site
Visit your domain:
- Home: `https://yourdomain.com/`
- Contact: `https://yourdomain.com/contact.php`
- Register: `https://yourdomain.com/register.php`
- Admin: `https://yourdomain.com/admin/login.php`

## Step 7 — Create Admin Account
Since the default admin was removed for security:
1. Visit `https://yourdomain.com/admin/login.php`
2. You will see the **Create Administrator** form.
3. Create your admin account with a strong password (8+ characters).
4. Log in and verify the dashboard works.

## Important Notes
- **Email delivery**: The `mail()` function may not work on some free hosts. If emails don't send, consider using PHPMailer with SMTP (Gmail, SendGrid, etc.).
- **HTTPS**: Most free hosts provide HTTPS automatically. Ensure your site URL uses `https://`.
- **APP_KEY**: Keep your APP_KEY secret. Do not share it or commit it to GitHub.
- **Backups**: Export your database regularly from phpMyAdmin.

## Recommended Free Hosts
- **InfinityFree** (infinityfree.net) — unlimited bandwidth, PHP + MySQL
- **000webhost** (000webhost.com) — easy setup, PHP + MySQL
- **AwardSpace** (awardspace.com) — free with ads optional
