# Deploy IT12 Dispatch System to Render.com (FREE)

## 🎯 Overview
This guide will help you deploy your IT12 Dispatch System from Railway to Render.com's free tier.

## 📋 Prerequisites
- ✅ GitHub account
- ✅ Git installed on your computer
- ✅ Your IT12 project ready
- ✅ Render.com account (free - sign up at https://render.com)

---

## 🚀 Step 1: Prepare Your Project for Render

### 1.1 Verify Required Files
Your project now includes:
- ✅ **render.yaml** - Render configuration
- ✅ **render-build.sh** - Build script
- ✅ **.env.example** - Environment template

### 1.2 Update .gitignore (if needed)
Ensure your .gitignore includes:
```
/node_modules
/vendor
.env
.env.backup
*.log
```

---

## 🗃️ Step 2: Push to GitHub

### 2.1 Initialize Git (if not already done)
```bash
cd C:\IT12_project\IT12_updated\IT12-
git init
```

### 2.2 Add Remote Repository
```bash
# If you have an existing repo
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git

# Or create a new repo on GitHub first, then:
git remote add origin https://github.com/YOUR_USERNAME/it12-dispatch-system.git
```

### 2.3 Commit and Push
```bash
git add .
git commit -m "Prepare for Render deployment"
git branch -M main
git push -u origin main
```

---

## 🎨 Step 3: Set Up Render.com

### 3.1 Create Render Account
1. Go to https://render.com
2. Click **"Get Started"**
3. Sign up with GitHub (recommended) or email

### 3.2 Create MySQL Database FIRST
**⚠️ Important: Create database before web service**

1. From Render Dashboard, click **"New +"**
2. Select **"PostgreSQL"** or find **"MySQL"** if available
   - **Note**: Render's free tier primarily offers PostgreSQL
   - **Alternative**: Use external MySQL (see Step 3.3)
3. Fill in details:
   - **Name**: it12-database
   - **Database**: it12_dispatch
   - **User**: it12_user
   - **Region**: Choose closest to your users
   - **Plan**: **Free**
4. Click **"Create Database"**
5. **⭐ SAVE THESE DETAILS** (you'll need them):
   - Internal Database URL
   - External Database URL
   - Hostname
   - Port
   - Database name
   - Username
   - Password

### 3.3 Alternative: Use External MySQL Database
If Render doesn't offer free MySQL or you prefer external hosting:

**Free MySQL Options:**
- **FreeSQLDatabase.com** (free 100MB)
- **db4free.net** (free with limitations)
- **Clever Cloud** (free tier available)
- **PlanetScale** (free tier available)

---

## 🌐 Step 4: Create Web Service on Render

### 4.1 Create New Web Service
1. From Render Dashboard, click **"New +"**
2. Select **"Web Service"**
3. Connect your GitHub repository
4. Select your **it12-dispatch-system** repository

### 4.2 Configure Web Service
Fill in the following:

**Basic Settings:**
- **Name**: it12-dispatch-system
- **Region**: Same as your database
- **Branch**: main
- **Root Directory**: (leave empty)
- **Runtime**: **PHP**
- **Build Command**: ./render-build.sh
- **Start Command**: php artisan serve --host=0.0.0.0 --port=\

### 4.3 Select Plan
- **Plan**: Select **Free**
- Free tier includes:
  - ✅ 750 hours/month
  - ✅ Automatic HTTPS
  - ✅ Auto-deploy from Git
  - ❌ Sleeps after 15 min of inactivity
  - ❌ Spins down with inactivity

---

## 🔧 Step 5: Configure Environment Variables

In the **Environment Variables** section, add these:

### Required Variables:
```
APP_NAME=IT12 Dispatch System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE
APP_URL=https://YOUR-APP-NAME.onrender.com

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOST
DB_PORT=3306
DB_DATABASE=it12_dispatch
DB_USERNAME=YOUR_DB_USERNAME
DB_PASSWORD=YOUR_DB_PASSWORD

CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true

QUEUE_CONNECTION=sync

SANCTUM_STATEFUL_DOMAINS=YOUR-APP-NAME.onrender.com
```

### 🔑 Generate APP_KEY:
Run this locally and copy the generated key:
```bash
php artisan key:generate --show
```

---

## 🎉 Step 6: Deploy

1. Click **"Create Web Service"**
2. Render will automatically:
   - Clone your repository
   - Run render-build.sh
   - Install dependencies
   - Build assets
   - Run migrations
   - Start your application
3. **Wait 5-10 minutes** for first deployment
4. Watch the deployment logs for any errors

---

## ✅ Step 7: Verify Deployment

### 7.1 Check Application
1. Once deployed, click your service URL
2. You should see your IT12 Dispatch System login page

### 7.2 Test Functionality
- ✅ Login works
- ✅ Database connections work
- ✅ Session handling works
- ✅ CSRF protection works

---

## 🔄 Step 8: Migrate Data from Railway (Optional)

### 8.1 Export Data from Railway
```bash
# Connect to Railway MySQL and export
mysqldump -h RAILWAY_HOST -u root -p railway > railway_backup.sql
```

### 8.2 Import to Render Database
```bash
# Import to Render database
mysql -h RENDER_HOST -u RENDER_USER -p RENDER_DATABASE < railway_backup.sql
```

---

## 🆚 Render vs Railway Comparison

| Feature | Railway | Render |
|---------|---------|--------|
| **Free Tier** | \ credit/month | 750 hours/month |
| **Database** | MySQL included | PostgreSQL (MySQL limited) |
| **Cold Starts** | Minimal | ~1 minute after sleep |
| **Auto-deploy** | ✅ Yes | ✅ Yes |
| **Custom Domain** | ✅ Yes | ✅ Yes |
| **SSL** | ✅ Auto | ✅ Auto |
| **Ease of Use** | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ |

---

## 🐛 Common Issues & Solutions

### Issue 1: Build Script Permission Denied
**Solution**: Make build script executable before pushing:
```bash
git update-index --chmod=+x render-build.sh
git commit -m "Make build script executable"
git push
```

### Issue 2: 419 CSRF Token Mismatch
**Solution**: Ensure these environment variables are set:
```
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=your-app.onrender.com
```

### Issue 3: Database Connection Failed
**Solution**: 
- Verify database credentials in environment variables
- Check database host (use internal host if same region)
- Ensure database is running

### Issue 4: Assets Not Loading
**Solution**: 
- Check APP_URL is set correctly
- Verify 
pm run build completed successfully
- Check storage permissions

### Issue 5: Application Sleeping
**Solution**: 
- Free tier sleeps after 15 min inactivity
- Consider using a cron job to ping your app every 14 minutes
- Or upgrade to paid tier for always-on service

---

## 🔄 Auto-Deploy Setup

Render automatically deploys when you push to GitHub:

```bash
# Make changes
git add .
git commit -m "Your changes"
git push

# Render will automatically detect and deploy!
```

---

## 📊 Monitoring & Logs

### View Logs:
1. Go to your service on Render dashboard
2. Click **"Logs"** tab
3. View real-time deployment and runtime logs

### Metrics:
1. Click **"Metrics"** tab
2. View:
   - Response times
   - Memory usage
   - CPU usage
   - Request counts

---

## 🎓 Next Steps

1. ✅ Set up custom domain (optional)
2. ✅ Configure automated backups
3. ✅ Set up monitoring alerts
4. ✅ Consider upgrading if you need:
   - Always-on service
   - More resources
   - Faster builds

---

## 📞 Support

**Render Documentation**: https://render.com/docs
**Render Community**: https://community.render.com
**Laravel Deployment**: https://laravel.com/docs/deployment

---

## 🎉 Congratulations!

Your IT12 Dispatch System is now running on Render.com! 🚀

**Your app is live at**: https://your-app-name.onrender.com

---

## 💡 Pro Tips

1. **Keep Your Repo Clean**: Always use .gitignore for sensitive files
2. **Monitor Logs**: Check logs regularly for errors
3. **Database Backups**: Set up automated backups for your database
4. **Use Environment Groups**: For managing multiple environments
5. **Branch Previews**: Deploy feature branches for testing before production

---

*Last Updated: January 2026*
