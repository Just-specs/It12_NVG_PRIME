# AWS EC2 Deployment Guide With RDS, S3, Nginx, SSL, And No-IP

This guide uses EC2 for the Laravel app, Amazon RDS for the MySQL database, and Amazon S3 for uploaded files such as driver photos, co-driver photos, vehicle photos, and receipt report files.

## 1. Launch The EC2 Instance

Create an Ubuntu EC2 instance in AWS.

Recommended settings:

- AMI: Ubuntu Server 24.04 LTS
- Instance type: `t3.micro` for testing
- Key pair: create or select a `.pem` key pair
- Storage: at least 20 GB

In the security group, check these options:

- Allow SSH traffic from your IP
- Allow HTTPS traffic from the internet
- Allow HTTP traffic from the internet

Click Launch instance.

## 2. Connect To The EC2 Instance

Open the folder where your `.pem` file is located.

In File Explorer, click the address bar, type:

```text
cmd
```

Then press Enter.

Connect using SSH:

```bash
ssh -i <name-of-your-key-pair>.pem ubuntu@<public-ip-of-your-instance> -v
```

Example:

```bash
ssh -i IT12-key.pem ubuntu@13.250.100.25 -v
```

If Windows says the key is too open, run this in PowerShell from the same folder:

```powershell
icacls .\<name-of-your-key-pair>.pem /inheritance:r
icacls .\<name-of-your-key-pair>.pem /grant:r "$env:USERNAME:R"
```

Then try the SSH command again.

## 3. Install Updates And Basic Packages

Update and upgrade the server:

```bash
sudo apt update -y
sudo apt upgrade -y
```

Install the packages from the guide:

```bash
sudo apt install tmux -y
sudo apt install nginx -y
sudo apt install certbot -y
```

Install the packages needed by this Laravel app:

```bash
sudo apt install git unzip curl mysql-client -y
sudo apt install php8.3-cli php8.3-fpm php8.3-mysql php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-zip php8.3-curl php8.3-gd -y
```

Install Composer:

```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version
```

Install Node.js 22:

```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install nodejs -y
node -v
npm -v
```

## 4. Clone The Laravel Application

Go to the server web directory:

```bash
cd /var/www
```

Clone your application:

```bash
sudo git clone <your-github-repository-url> IT12
```

Example:

```bash
sudo git clone https://github.com/your-username/your-repository.git IT12
```

Set ownership:

```bash
sudo chown -R ubuntu:www-data /var/www/IT12
cd /var/www/IT12
```

## 5. Install Application Dependencies

Install PHP dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

Install JavaScript dependencies and build the frontend:

```bash
npm ci
npm run build
```

Create the `.env` file:

```bash
cp .env.example .env
nano .env
```

Use values like these first:

```env
APP_NAME="IT12"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://<public-ip-of-your-instance>

DB_CONNECTION=mysql
DB_HOST=<your-rds-endpoint>
DB_PORT=3306
DB_DATABASE=dispatch
DB_USERNAME=<your-rds-master-username>
DB_PASSWORD=<your-rds-password>

SESSION_DRIVER=file
QUEUE_CONNECTION=database
CACHE_STORE=file

FILESYSTEM_DISK=local
```

Generate the Laravel app key:

```bash
php artisan key:generate
```

## 6. Create The Amazon RDS MySQL Database

Create an RDS MySQL database in AWS:

1. Go to AWS Console.
2. Open RDS.
3. Click Create database.
4. Choose Standard create.
5. Engine: MySQL.
6. Template: Free tier for testing, Production for real deployment.
7. DB instance identifier:

```text
nvgprime-db
```

8. Master username:

```text
admin
```

9. Set and save a strong master password.
10. Choose the same VPC as your EC2 instance.
11. Public access:
    - Choose No if only EC2 will connect.
    - Choose Yes only if you need to connect from your laptop.
12. Create or select a security group for RDS.

### Allow EC2 To Connect To RDS

In the RDS security group, add an inbound rule:

- Type: MySQL/Aurora
- Port: `3306`
- Source: the EC2 security group ID

This is safer than opening MySQL to the whole internet.

### Create The App Database In RDS

Copy the RDS endpoint from AWS. It looks like:

```text
nvgprime-db.xxxxxxxxxxxx.ap-southeast-1.rds.amazonaws.com
```

From EC2, connect to RDS:

```bash
mysql -h <your-rds-endpoint> -u <your-rds-master-username> -p
```

Create the Laravel database:

```sql
CREATE DATABASE IF NOT EXISTS dispatch CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Update EC2 `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=<your-rds-endpoint>
DB_PORT=3306
DB_DATABASE=dispatch
DB_USERNAME=<your-rds-master-username>
DB_PASSWORD=<your-rds-password>
```

Then run:

```bash
cd /var/www/IT12
php artisan config:clear
php artisan migrate --force
```

Fix Laravel permissions:

```bash
sudo chown -R ubuntu:www-data /var/www/IT12
sudo chmod -R 775 storage bootstrap/cache
```

Cache Laravel settings:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 7. Run The Application In Tmux

Create a `tmux` session:

```bash
tmux
```

Run the Laravel app on port `8000`:

```bash
cd /var/www/IT12
php artisan serve --host=127.0.0.1 --port=8000
```

Detach from the `tmux` session:

```text
Ctrl+b then d
```

To return to the session later:

```bash
tmux attach
```

To see existing sessions:

```bash
tmux ls
```

## 8. Create A Free Domain With No-IP

1. Go to `https://www.noip.com/`.
2. Sign up for a free account.
3. Go to `https://my.noip.com/`.
4. Create a free dynamic DNS hostname.
5. Point the hostname to your EC2 public IP address.

Example hostname:

```text
it12-demo.ddns.net
```

After creating the hostname, use it as your app domain.

Update the Laravel `.env` file:

```bash
cd /var/www/IT12
nano .env
```

Set:

```env
APP_URL=http://<your-free-domain>
```

Then refresh config:

```bash
php artisan config:clear
php artisan config:cache
```

## 9. Get An SSL Certificate Using Certbot

Before creating the certificate, stop Nginx if it is already running:

```bash
sudo systemctl stop nginx
```

Generate the SSL certificate:

```bash
sudo certbot certonly --standalone -d <your-free-domain>
```

Example:

```bash
sudo certbot certonly --standalone -d it12-demo.ddns.net
```

If Certbot asks for an email address, enter your email. Agree to the terms.

## 10. Set Up Nginx Reverse Proxy

Go to the Nginx sites folder:

```bash
cd /etc/nginx/sites-available
```

Edit the default site:

```bash
sudo nano default
```

Replace the contents with this config. Change `<your-free-domain>` to your real No-IP domain:

```nginx
server {
    server_name <your-free-domain>;

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

    listen 443 ssl;
    ssl_certificate /etc/letsencrypt/live/<your-free-domain>/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/<your-free-domain>/privkey.pem;
}

server {
    listen 80;
    server_name <your-free-domain>;
    return 301 https://$host$request_uri;
}
```

Test Nginx:

```bash
sudo nginx -t
```

Start and enable Nginx:

```bash
sudo systemctl enable nginx
sudo systemctl start nginx
```

Open your free domain in the browser:

```text
https://<your-free-domain>
```

## 11. Update Laravel For HTTPS

After SSL is working, update `.env`:

```bash
cd /var/www/IT12
nano .env
```

Set:

```env
APP_URL=https://<your-free-domain>
SESSION_SECURE_COOKIE=true
```

Refresh Laravel config:

```bash
php artisan config:clear
php artisan config:cache
```

## 12. Use AWS S3 Bucket For Uploads

Use S3 for uploaded files. In this project, S3 can store driver photos, co-driver photos, vehicle photos, and receipt report uploads. RDS stores database records; S3 stores the actual files.

### Create The S3 Bucket

1. Open AWS Console.
2. Go to S3.
3. Click Create bucket.
4. Enter a unique bucket name.

Example:

```text
it12-production-uploads
```

Choose your AWS region, for example:

```text
ap-southeast-1
```

Keep Block all public access enabled if uploads should be private.

### Create IAM User For S3

Create a dedicated IAM user for the Laravel app.

Example IAM username:

```text
it12-s3-user
```

Create an access key for the user and save:

- Access key ID
- Secret access key

Attach this policy to the IAM user. Replace `it12-production-uploads` with your real bucket name:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "s3:PutObject",
                "s3:GetObject",
                "s3:DeleteObject",
                "s3:ListBucket"
            ],
            "Resource": [
                "arn:aws:s3:::it12-production-uploads",
                "arn:aws:s3:::it12-production-uploads/*"
            ]
        }
    ]
}
```

### Install Laravel S3 Driver

From the project folder:

```bash
cd /var/www/IT12
composer require league/flysystem-aws-s3-v3 "^3.0"
```

If you do not want this dependency in local Git yet, install it directly on EC2 after pulling your code.

### Add S3 To `.env`

Edit `.env`:

```bash
nano .env
```

Set:

```env
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=<your-iam-access-key-id>
AWS_SECRET_ACCESS_KEY=<your-iam-secret-access-key>
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=it12-production-uploads
AWS_USE_PATH_STYLE_ENDPOINT=false
```

If your bucket files are public, add:

```env
AWS_URL=https://it12-production-uploads.s3.ap-southeast-1.amazonaws.com
```

If your bucket files are private, do not add `AWS_URL`.

For public browser-openable uploads, add `AWS_URL` and allow public read on the bucket. For private files, keep the bucket private and use temporary URLs in code.

Refresh Laravel config:

```bash
php artisan config:clear
php artisan config:cache
```

### Test S3 Upload

Open Laravel Tinker:

```bash
php artisan tinker
```

Run:

```php
Storage::disk('s3')->put('test/hello.txt', 'Hello from Laravel EC2 to S3');
Storage::disk('s3')->exists('test/hello.txt');
```

If it returns `true`, S3 is working.

Delete the test file:

```php
Storage::disk('s3')->delete('test/hello.txt');
```

## 13. Useful Commands

Restart the Laravel app:

```bash
tmux attach
```

Stop the current Laravel server with:

```text
Ctrl+c
```

Then run again:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Detach again:

```text
Ctrl+b then d
```

Restart Nginx:

```bash
sudo systemctl restart nginx
```

Check Laravel logs:

```bash
cd /var/www/IT12
tail -f storage/logs/laravel.log
```

Check Nginx logs:

```bash
sudo tail -f /var/log/nginx/error.log
```

## 14. Future Updates

When you push new code to GitHub, update EC2 like this:

```bash
cd /var/www/IT12
git pull
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Then restart the app inside `tmux`.

## Deployment Checklist

- [ ] EC2 instance launched
- [ ] HTTP and HTTPS traffic allowed
- [ ] SSH connected using `.pem` file
- [ ] Server updated with `sudo apt update -y` and `sudo apt upgrade -y`
- [ ] `tmux`, `nginx`, and `certbot` installed
- [ ] PHP, Composer, Node.js, Git, and MySQL installed
- [ ] RDS MySQL database created
- [ ] RDS security group allows MySQL from EC2 security group
- [ ] Laravel app cloned into `/var/www/IT12`
- [ ] Composer dependencies installed
- [ ] NPM dependencies installed and frontend built
- [ ] `.env` configured
- [ ] `.env` points `DB_HOST` to the RDS endpoint
- [ ] Migrations run
- [ ] App running in `tmux` on port `8000`
- [ ] No-IP free domain points to EC2 public IP
- [ ] Certbot SSL certificate created
- [ ] Nginx reverse proxy configured
- [ ] App opens at `https://<your-free-domain>`
- [ ] S3 bucket configured for uploads
- [ ] `league/flysystem-aws-s3-v3` installed on EC2 if `FILESYSTEM_DISK=s3`
