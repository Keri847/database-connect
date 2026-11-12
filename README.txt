PHP + MySQL Login Template

REQUIREMENTS
- PHP 8.0+ with PDO MySQL enabled
- MySQL or MariaDB
- XAMPP, WAMP, MAMP, or a PHP web server

INSTALLATION (XAMPP)
1. Extract this folder into C:\xampp\htdocs\
   The folder should be named php_mysql_login_template.
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Open http://localhost/phpmyadmin
4. Import database.sql (or paste its contents into the SQL tab).
5. Check config/database.php and update the MySQL username/password if needed.
6. Visit:
   http://localhost/php_mysql_login_template/register.php
7. Create an account, then sign in at:
   http://localhost/php_mysql_login_template/index.php

FILES
- database.sql: creates the database and users table
- config/database.php: PDO database connection
- config/bootstrap.php: sessions, output escaping, CSRF helpers
- register.php: account creation and password hashing
- index.php: login form
- login.php: password verification and session creation
- dashboard.php: protected account dashboard
- logout.php: POST-based logout
- style.css: responsive page styling

SECURITY NOTES
- Passwords are stored using password_hash(), never as plain text.
- Login uses password_verify().
- Database operations use PDO prepared statements.
- Forms include CSRF tokens.
- Session IDs are regenerated after login.
- Use HTTPS in production and set secure cookies.
- Add rate limiting, email verification, and password reset before public deployment.
- Do not use the default XAMPP root account with an empty password on a public server.
- The "Remember me" and "Forgot password" features are intentionally not implemented.
