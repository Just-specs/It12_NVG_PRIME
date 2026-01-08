# Deploy Laravel to Railway.app (FREE)

## Prerequisites
- ? GitHub account (create at https://github.com if you don't have one)
- ? Git installed on your computer
- ? Your IT12 Dispatch System project

---

## Step 1: Prepare Your Laravel Project (Detailed Guide)

This step prepares your Laravel project for Railway deployment. Follow each sub-step carefully.

---

### 1.1 Open Your Project in Command Prompt

```bash
# Navigate to your project folder
cd C:\IT12_project\IT12_updated\IT12-

# Verify you're in the right folder (should show files like artisan, composer.json)
dir
```

**? You should see:** artisan, composer.json, app folder, etc.

---

### 1.2 Create Required Files for Railway

Railway needs specific files to know how to deploy your Laravel app. Let's create them one by one.

#### A. Create `.railwayignore` file

**What it does:** Tells Railway which files NOT to upload (saves time and space)

**How to create:**
1. Open Notepad
2. Copy and paste this content:
```
/node_modules
/vendor
.env
.env.backup
/storage/*.key
/storage/logs
/storage/framework/cache
/storage/framework/sessions
/storage/framework/views
```
3. Save as: `C:\IT12_project\IT12_updated\IT12-\.railwayignore` (with the dot at the start)
4. Make sure "Save as type" is "All Files" (not .txt)

**Or use Command Prompt:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

echo /node_modules > .railwayignore
echo /vendor >> .railwayignore
echo .env >> .railwayignore
echo .env.backup >> .railwayignore
echo /storage/*.key >> .railwayignore
echo /storage/logs >> .railwayignore
echo /storage/framework/cache >> .railwayignore
echo /storage/framework/sessions >> .railwayignore
echo /storage/framework/views >> .railwayignore
```

---

#### B. Create `Procfile`

**What it does:** Tells Railway how to start your Laravel application

**How to create:**
1. Open Notepad
2. Copy and paste this EXACT line:
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```
3. Save as: `C:\IT12_project\IT12_updated\IT12-\Procfile` (no file extension!)
4. "Save as type": All Files

**Or use Command Prompt:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

echo web: php artisan serve --host=0.0.0.0 --port=$PORT > Procfile
```

**?? Important:** The file must be named exactly `Procfile` (capital P, no extension)

---

#### C. Create `nixpacks.toml`

**What it does:** Tells Railway which PHP version and packages to use

**How to create:**
1. Open Notepad
2. Copy and paste this content:
```toml
[phases.setup]
nixPkgs = ["php82", "php82Packages.composer"]

[phases.install]
cmds = ["composer install --no-dev --optimize-autoloader"]

[phases.build]
cmds = ["npm install && npm run build"]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
```
3. Save as: `C:\IT12_project\IT12_updated\IT12-\nixpacks.toml`
4. "Save as type": All Files

**Or create using Command Prompt:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

(
echo [phases.setup]
echo nixPkgs = ["php82", "php82Packages.composer"]
echo.
echo [phases.install]
echo cmds = ["composer install --no-dev --optimize-autoloader"]
echo.
echo [phases.build]
echo cmds = ["npm install && npm run build"]
echo.
echo [start]
echo cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
) > nixpacks.toml
```

---

### 1.3 Update composer.json (Optional but Recommended)

**What it does:** Automatically runs Laravel optimization after deployment

1. Open `composer.json` in your text editor
2. Find the `"scripts"` section
3. Add or update it to include:

```json
"scripts": {
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ],
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
    ],
    "post-root-package-install": [
        "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
    ],
    "post-create-project-cmd": [
        "@php artisan key:generate --ansi"
    ]
}
```

**?? Tip:** If unsure, you can skip this step. It's optional.

---

### 1.4 Verify Your Files

Make sure these files now exist in your project root:

```bash
cd C:\IT12_project\IT12_updated\IT12-
dir

# You should see:
# - Procfile (no extension)
# - nixpacks.toml
# - .railwayignore (hidden file)
```

To see hidden files in Windows Explorer: View ? Show ? Hidden items

---

### 1.5 Initialize Git Repository (If Not Already Done)

**Check if Git is already initialized:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

# Check if .git folder exists
dir /a
```

**If you DON'T see `.git` folder, initialize Git:**

```bash
# Initialize Git
git init

# Configure Git (replace with your info)
git config user.name "Your Name"
git config user.email "your-email@example.com"
```

**If you DO see `.git` folder:**
? Git is already initialized, skip to next step

---

### 1.6 Create .gitignore File (If Not Exists)

**Check if `.gitignore` exists:**
```bash
cd C:\IT12_project\IT12_updated\IT12-
dir .gitignore
```

**If it doesn't exist, create it:**

1. Create file `.gitignore` in project root
2. Add this content:

```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode
```

**Or use Command Prompt:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

(
echo /node_modules
echo /public/hot
echo /public/storage
echo /storage/*.key
echo /vendor
echo .env
echo .env.backup
echo .phpunit.result.cache
echo npm-debug.log
echo yarn-error.log
) > .gitignore
```

---

### 1.7 Stage Your Files for Git

**Add all files to Git:**
```bash
cd C:\IT12_project\IT12_updated\IT12-

# Add all files
git add .

# Check what will be committed
git status
```

**? You should see:**
- Procfile
- nixpacks.toml
- .railwayignore
- All your Laravel files

**?? You should NOT see:**
- node_modules/
- vendor/
- .env

---

### 1.8 Commit Your Changes

```bash
cd C:\IT12_project\IT12_updated\IT12-

# Commit with a message
git commit -m "Prepare IT12 Dispatch System for Railway deployment"
```

**? Success message:** Should say files committed successfully

---

### 1.9 Create GitHub Repository

#### Option A: Using GitHub Website (Easier)

1. **Go to GitHub:** https://github.com
2. **Login** with your account
3. **Click** the "+" icon (top right) ? "New repository"
4. **Repository name:** `it12-dispatch-system` (or any name you like)
5. **Description:** "IT12 Prime Movers Dispatch System"
6. **Visibility:** Choose "Public" or "Private"
7. **DON'T** check "Initialize with README" (we already have files)
8. **Click** "Create repository"

**? GitHub will show you commands.** Copy the section that says:
```
git remote add origin https://github.com/YOUR-USERNAME/it12-dispatch-system.git
git branch -M main
git push -u origin main
```

---

### 1.10 Push to GitHub

**Use the commands GitHub gave you:**

```bash
cd C:\IT12_project\IT12_updated\IT12-

# Add GitHub as remote (replace with YOUR GitHub username)
git remote add origin https://github.com/YOUR-USERNAME/it12-dispatch-system.git

# Rename branch to main
git branch -M main

# Push to GitHub
git push -u origin main
```

**First time?** GitHub will ask for your username and password.

**?? Note:** GitHub now requires Personal Access Token instead of password.

**To create a token:**
1. GitHub ? Settings ? Developer settings ? Personal access tokens ? Tokens (classic)
2. Generate new token ? Give it repo permissions
3. Copy the token (you won't see it again!)
4. Use this token as your password when Git asks

---

### 1.11 Verify Upload Success

1. **Go to your GitHub repository** in your browser
2. **Refresh the page**
3. **? You should see:**
   - All your Laravel files
   - Procfile
   - nixpacks.toml
   - README files

**? Step 1 Complete!** Your project is now on GitHub and ready for Railway.

---

## Step 2: Deploy to Railway

### 2.1 Sign Up for Railway

1. **Go to:** https://railway.app
2. **Click:** "Login with GitHub"
3. **Authorize:** Railway to access your GitHub
4. **Verify Email:** Check your email and verify
5. **Add Credit Card:** Required but won't charge (Railway gives $5 free credit)

**? You're now on Railway Dashboard**

---

### 2.2 Create New Project

1. **Click:** "New Project" (big button in center or top right)
2. **Select:** "Deploy from GitHub repo"
3. **Choose:** Your repository `it12-dispatch-system`
4. **Wait:** Railway will detect it's a PHP/Laravel project (~30 seconds)

**? Railway creates your service automatically**

---

### 2.3 Add MySQL Database

Your Laravel app needs a database. Let's add one:

1. **In your project:** Click "+ New" button (top right)
2. **Select:** "Database"
3. **Choose:** "Add MySQL"
4. **Wait:** Railway provisions database (~1 minute)

**? MySQL service appears** in your project

---

### 2.4 Configure Environment Variables

Now we tell Laravel where the database is:

1. **Click** on your Laravel service (the one with your repo name)
2. **Go to:** "Variables" tab
3. **Click:** "RAW Editor" button (easier to paste)
4. **Delete** everything in the editor
5. **Copy and paste** this:

```env
APP_NAME="IT12 Dispatch System"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:WILL_BE_GENERATED_LATER
APP_URL=https://your-app-name.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

6. **Click:** "Update Variables"

**? Variables saved!** Railway will automatically redeploy.

---

### 2.5 Generate APP_KEY

We need to generate a secure key for Laravel:

**Option A: Use Local Machine**
```bash
cd C:\IT12_project\IT12_updated\IT12-
php artisan key:generate --show
```

Copy the output (should look like: `base64:xxxxxxxxxxx`)

**Option B: Use Online Generator**
1. Go to: https://generate-random.org/laravel-key-generator
2. Click "Generate Laravel Key"
3. Copy the generated key

**Add the key to Railway:**
1. Go back to Railway ? Variables tab
2. Find `APP_KEY`
3. Replace the value with your generated key
4. Update variables

**? APP_KEY is set!**

---

### 2.6 Get Your App URL

1. **Click** on your Laravel service
2. **Go to:** "Settings" tab
3. **Find:** "Domains" section
4. **Click:** "Generate Domain"
5. **Wait:** Railway generates a URL (~10 seconds)

**? You get:** `https://your-app-xxxx.up.railway.app`

---

### 2.7 Update APP_URL

Now update the APP_URL variable with your real Railway URL:

1. **Go to:** Variables tab
2. **Find:** `APP_URL`
3. **Replace:** with your Railway URL (from step 2.6)
4. **Click:** "Update Variables"

**? Railway redeploys automatically**

---

## Step 3: Run Database Migrations

Your database is empty. Let's add tables:

### 3.1 Wait for Deployment to Finish

1. **Go to:** "Deployments" tab
2. **Wait** until status shows "SUCCESS" (green checkmark)
3. **Takes:** ~2-3 minutes

---

### 3.2 Open Railway CLI

**Option A: Use Railway CLI (If Installed)**
```bash
# Install Railway CLI first
npm install -g @railway/cli

# Login
railway login

# Link to project
railway link

# Run migrations
railway run php artisan migrate --force
```

**Option B: Use One-Click Migration (Simpler)**

Create a temporary migration endpoint:

1. **On your local project**, create file: `public/migrate.php`

```php
<?php
// TEMPORARY FILE - DELETE AFTER USE!
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('migrate', ['--force' => true]);
echo "Migrations completed!";
```

2. **Commit and push:**
```bash
git add public/migrate.php
git commit -m "Add migration script"
git push
```

3. **Wait** for Railway to redeploy

4. **Visit:** `https://your-app-xxxx.up.railway.app/migrate.php`

5. **You should see:** "Migrations completed!"

6. **IMMEDIATELY DELETE** the file:
```bash
git rm public/migrate.php
git commit -m "Remove migration script"
git push
```

**? Database tables created!**

---

### 3.3 Create Admin User

Create an admin account to login:

**Create:** `public/create-admin.php`

```php
<?php
// TEMPORARY FILE - DELETE AFTER USE!
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@it12dispatch.com';
$user->password = Hash::make('admin123');
$user->role = 'admin';
$user->save();

echo "Admin user created!<br>";
echo "Email: admin@it12dispatch.com<br>";
echo "Password: admin123<br>";
echo "<br>DELETE THIS FILE IMMEDIATELY!";
```

**Commit, push, visit, then DELETE immediately!**

---

## Step 4: Test Your Deployment

### 4.1 Visit Your App

Go to: `https://your-app-xxxx.up.railway.app`

**? You should see:** Your IT12 Dispatch System login page

---

### 4.2 Login and Test

1. **Login** with: `admin@it12dispatch.com` / `admin123`
2. **Test these features:**
   - ? Dashboard loads
   - ? Clients page works
   - ? Drivers page works
   - ? Vehicles page works
   - ? Requests page works
   - ? Trips page works
   - ? Can create records
   - ? Duplicate prevention works
   - ? Archive filter visible (admin only)
   - ? Cancel trip button visible (admin only)

---

### 4.3 Change Admin Password

**Important:** Change the default password!

1. Go to your profile or settings
2. Change password from `admin123` to something secure

---

## Step 5: Set Up Auto-Archiving (Optional)

For the 7-day auto-archive feature to work:

### 5.1 Create Worker Service

1. **In Railway project:** Click "+ New"
2. **Select:** "Empty Service"
3. **Name it:** "scheduler"
4. **Connect** to your GitHub repository
5. **In Variables**, add:
```env
APP_KEY=${{Laravel.APP_KEY}}
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
```

### 5.2 Update Start Command

In scheduler service:
1. Go to Settings
2. Find "Start Command"
3. Set to: `php artisan schedule:work`

**? Auto-archiving now runs daily!**

---

## Step 6: Security & Cleanup

### 6.1 Remove Temporary Files

Make sure these are deleted:
- ? `public/migrate.php`
- ? `public/create-admin.php`

### 6.2 Update .env Security

In Railway Variables:
```env
APP_DEBUG=false
APP_ENV=production
```

---

## ?? Deployment Complete!

Your IT12 Dispatch System is now live at:
`https://your-app-xxxx.up.railway.app`

---

## Troubleshooting

### Issue: "500 Server Error"
**Solution:**
1. Check Railway logs (Deployments tab)
2. Verify APP_KEY is set correctly
3. Check database variables

### Issue: "Database Connection Error"
**Solution:**
1. Verify MySQL variables use `${{MySQL.*}}` syntax
2. Check MySQL service is running
3. Redeploy if needed

### Issue: CSS/JS Not Loading
**Solution:**
1. Check APP_URL is correct
2. Run `npm run build` locally and push
3. Clear browser cache

### Issue: Login Not Working
**Solution:**
1. Verify admin user was created
2. Check sessions are working
3. Try password reset

---

## Next Steps

1. ? Share URL with team/professor
2. ? Test all features thoroughly
3. ? Add real data
4. ? Monitor usage (stay within $5/month)
5. ? Set up backups

---

## Support

- **Railway Docs:** https://docs.railway.app
- **Railway Discord:** Community support
- **Laravel Docs:** https://laravel.com/docs

---

**Your Laravel app is deployed for FREE! ??**

**Total time:** ~15-20 minutes
**Cost:** $0/month (within $5 free credit)
