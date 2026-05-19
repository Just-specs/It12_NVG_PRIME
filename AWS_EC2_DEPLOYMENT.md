# Local EC2 Laravel Deployment And Debug Guide

This is the local EC2 setup for this project. Use this as your main reference while debugging the app on your EC2 instance.

Setup:

- EC2 runs Laravel.
- EC2 runs MySQL locally.
- EC2 stores uploaded files locally in Laravel storage.
- Nginx forwards your domain to Laravel on port `8000`.
- `tmux` keeps Laravel running after you close SSH.

RDS and S3 can be added later, but this guide keeps the current EC2 setup simple and easier to debug.

## 1. Connect To EC2

From the folder where `nvg.pem` is located:

```bash
ssh -i nvg.pem ubuntu@<your-ec2-public-ip>
```

Go to the project:

```bash
cd ~/It12_NVG_PRIME
```

## 2. Install Server Packages

```bash
sudo apt update -y
sudo apt upgrade -y
sudo apt install tmux nginx certbot git unzip curl mysql-server -y
sudo apt install php8.3-cli php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-zip php8.3-curl php8.3-gd -y
```

Install Composer if missing:

```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version
```

Install Node.js:

```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install nodejs -y
node -v
npm -v
```

## 3. Local EC2 `.env`

Edit:

```bash
nano .env
```

Use this for local EC2 MySQL and local uploads:

```env
APP_NAME="NVG Prime"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nvgprime.hopto.org
TRUSTED_PROXIES=127.0.0.1

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dispatch
DB_USERNAME=dispatch_user
DB_PASSWORD=your_mysql_password

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

SESSION_SECURE_COOKIE=true
```

Important for browser form security warnings:

- Keep `APP_URL` as `https://nvgprime.hopto.org` after SSL is installed.
- Keep `TRUSTED_PROXIES=127.0.0.1` because Nginx sends HTTPS requests to Laravel through the local proxy.
- Rebuild Laravel's config cache after changing `.env`.

If debugging and you need to see the real Laravel error temporarily:

```env
APP_DEBUG=true
```

Turn it back off after debugging:

```env
APP_DEBUG=false
```

## 4. Create Local MySQL Database

Open MySQL:

```bash
sudo mysql
```

Create database and user:

```sql
CREATE DATABASE IF NOT EXISTS dispatch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'dispatch_user'@'localhost';
CREATE USER 'dispatch_user'@'localhost' IDENTIFIED BY 'your_mysql_password';
GRANT ALL PRIVILEGES ON dispatch.* TO 'dispatch_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Test login:

```bash
mysql -u dispatch_user -p dispatch
```

Then exit:

```sql
EXIT;
```

## 5. Install App Dependencies

```bash
cd ~/It12_NVG_PRIME
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

If `.env` is missing:

```bash
cp .env.example .env
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate --force
```

Create local storage link:

```bash
php artisan storage:link
```

Fix permissions:

```bash
sudo chown -R ubuntu:www-data ~/It12_NVG_PRIME
sudo chmod -R 775 storage bootstrap/cache
```

Clear and cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6. Run Laravel In Tmux

Start or attach to tmux:

```bash
tmux
```

Run Laravel:

```bash
cd ~/It12_NVG_PRIME
php artisan serve --host=127.0.0.1 --port=8000
```

Detach without stopping Laravel:

```text
Ctrl+b then d
```

Show tmux sessions:

```bash
tmux ls
```

Attach again:

```bash
tmux attach
```

## 7. Nginx Local Proxy

Edit Nginx:

```bash
sudo nano /etc/nginx/sites-available/default
```

HTTP-only debugging config:

```nginx
server {
    listen 80;
    server_name nvgprime.hopto.org;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_http_version 1.1;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

Test and restart:

```bash
sudo nginx -t
sudo systemctl restart nginx
```

Open:

```text
http://nvgprime.hopto.org
```

Use the HTTP-only config only for initial debugging. Forms submitted over HTTP are not secure, and browsers may warn before sending login, registration, or dispatch data. After SSL is installed, use the HTTPS config in the next section.

## 8. SSL Config

Stop Nginx before standalone Certbot:

```bash
sudo systemctl stop nginx
sudo certbot certonly --standalone -d nvgprime.hopto.org
```

Then use this Nginx config:

```nginx
server {
    listen 443 ssl;
    server_name nvgprime.hopto.org;

    ssl_certificate /etc/letsencrypt/live/nvgprime.hopto.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/nvgprime.hopto.org/privkey.pem;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}

server {
    listen 80;
    server_name nvgprime.hopto.org;
    return 301 https://$host$request_uri;
}
```

Test and restart:

```bash
sudo nginx -t
sudo systemctl start nginx
sudo systemctl restart nginx
```

## 9. Import Local XAMPP Database To EC2

On Windows, export from XAMPP:

```bat
cd C:\xampp\mysql\bin
mysqldump -u root --databases dispatch --result-file=C:\xampp\htdocs\dispatch.sql
```

Upload to EC2 from the folder where `nvg.pem` is located:

```bash
scp -i nvg.pem C:\xampp\htdocs\dispatch.sql ubuntu@<your-ec2-public-ip>:/home/ubuntu/dispatch.sql
```

If the file is UTF-16, convert it:

```bash
iconv -f UTF-16 -t UTF-8 /home/ubuntu/dispatch.sql > /home/ubuntu/dispatch_utf8.sql
```

Import:

```bash
sudo mysql < /home/ubuntu/dispatch_utf8.sql
```

Verify:

```bash
sudo mysql
```

```sql
USE dispatch;
SHOW TABLES;
SELECT id, name, email, role FROM users;
EXIT;
```

## 10. Uploads Stored Locally

For local EC2 storage, use:

```env
FILESYSTEM_DISK=local
```

Run:

```bash
php artisan storage:link
```

Uploaded files will be stored in:

```text
storage/app/public
```

And served from:

```text
public/storage
```

## 11. Debug Commands

Check Laravel:

```bash
curl http://127.0.0.1:8000
```

Check Nginx:

```bash
sudo nginx -t
sudo systemctl status nginx
sudo tail -f /var/log/nginx/error.log
```

Check Laravel logs:

```bash
cd ~/It12_NVG_PRIME
tail -f storage/logs/laravel.log
```

Check MySQL:

```bash
mysql -u dispatch_user -p dispatch
```

Check PHP extensions:

```bash
php -m | grep -E "pdo_mysql|dom|xml|mbstring|curl|zip"
```

Check port `8000`:

```bash
sudo ss -ltnp | grep ':8000'
```

If Nginx shows `connect() failed (111: Connection refused) while connecting to upstream`, Nginx is running but Laravel is not listening on port `8000`. Restart Laravel in a named tmux session:

```bash
cd ~/It12_NVG_PRIME
tmux kill-session -t laravel 2>/dev/null || true
tmux new -d -s laravel "cd ~/It12_NVG_PRIME && php artisan serve --host=127.0.0.1 --port=8000"
tmux ls
sudo ss -ltnp | grep ':8000'
curl -I http://127.0.0.1:8000
```

Check port `80`:

```bash
sudo ss -ltnp | grep ':80'
```

Fix insecure form submission warning:

```bash
cd ~/It12_NVG_PRIME
grep -E "^(APP_URL|TRUSTED_PROXIES|SESSION_SECURE_COOKIE)=" .env
php artisan optimize:clear
php artisan config:cache
```

Expected values:

```env
APP_URL=https://nvgprime.hopto.org
TRUSTED_PROXIES=127.0.0.1
SESSION_SECURE_COOKIE=true
```

Then restart Laravel in tmux and open:

```text
https://nvgprime.hopto.org
```

## 12. Updating Code On EC2

```bash
cd ~/It12_NVG_PRIME
git pull
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Restart Laravel in tmux:

```bash
tmux attach
```

Press:

```text
Ctrl+c
```

Run:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Detach:

```text
Ctrl+b then d
```

## 13. Optional Later: RDS And S3

Use RDS later when you want AWS-managed MySQL.

Use S3 later when you want uploaded files outside EC2.

For now, keep this local EC2 setup while debugging:

```env
DB_HOST=127.0.0.1
FILESYSTEM_DISK=local
```

## Checklist

- [ ] EC2 SSH works
- [ ] MySQL runs locally on EC2
- [ ] `.env` uses `DB_HOST=127.0.0.1`
- [ ] `.env` uses `FILESYSTEM_DISK=local`
- [ ] `php artisan migrate --force` works
- [ ] `php artisan storage:link` works
- [ ] Laravel runs in tmux on port `8000`
- [ ] Nginx proxies to `127.0.0.1:8000`
- [ ] Domain opens through Nginx
- [ ] Laravel logs are clean
