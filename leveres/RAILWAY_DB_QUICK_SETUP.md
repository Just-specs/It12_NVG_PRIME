# Quick Guide: Deploy Render Web + Keep Railway Database

## 🎯 What We're Doing
- ✅ Deploy web app on Render.com (FREE)
- ✅ Keep MySQL database on Railway.app (FREE)
- ✅ Connect them together via internet

---

## 🔑 Step 1: Get Railway Database PUBLIC Connection

### Option A: Railway Dashboard
1. Go to https://railway.app
2. Open your IT12 project
3. Click **MySQL service**
4. Click **"Connect"** tab
5. Look for **"Public URL"** or **"TCP Proxy"**
6. Copy the **hostname** and **port**

Example:
```
Host: containers-us-west-123.railway.app
Port: 7890 (or 3306)
```

### Option B: Railway Variables Tab
1. Go to MySQL service → **"Variables"** tab
2. Look for these variables:
   - MYSQLHOST or MYSQL_PUBLIC_HOST
   - MYSQLPORT or MYSQL_PUBLIC_PORT
3. Use these for external connection

⚠️ **IMPORTANT**: 
- DO NOT use mysql.railway.internal (internal only)
- You need the PUBLIC hostname that works from internet

---

## 📝 Step 2: Prepare Environment Variables

Copy this and fill in YOUR Railway database details:

```env
APP_NAME=IT12 Dispatch System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ZpA2I/aeKDjUsxjUUDILwLqqLxMGlmU9tqJZnTgdEB8=
APP_URL=https://it12-dispatch-system.onrender.com

# Railway Database (Update these!)
DB_CONNECTION=mysql
DB_HOST=containers-us-west-XXX.railway.app    # ← Your Railway public host
DB_PORT=7890                                    # ← Your Railway public port
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=vkYuzamohnYfLsGrpeNdmqzRAGUuoaIj  # ← Already correct

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=it12-dispatch-system.onrender.com
```

---

## 🚀 Step 3: Deploy to Render

### 3.1 Push to GitHub
```bash
cd C:\IT12_project\IT12_updated\IT12-
git add .
git commit -m "Configure Render with Railway MySQL database"
git push origin main
```

### 3.2 Create Web Service on Render
1. Go to https://render.com
2. Sign up/Login
3. Click **"New +"** → **"Web Service"**
4. Connect GitHub repo
5. Select your repository

### 3.3 Configure Service
- **Name**: it12-dispatch-system
- **Build Command**: ./render-build.sh
- **Start Command**: php artisan serve --host=0.0.0.0 --port=\
- **Plan**: **Free**

### 3.4 Add Environment Variables
Paste all the variables from Step 2 above.

Make sure to update:
- APP_URL with your actual Render URL
- DB_HOST with Railway public host
- DB_PORT with Railway public port
- SANCTUM_STATEFUL_DOMAINS with Render URL

### 3.5 Deploy!
Click **"Create Web Service"** and wait 5-10 minutes.

---

## ✅ Step 4: Verify It Works

1. Visit your Render URL
2. Try logging in
3. Check if your Railway data appears
4. Test all features

---

## 🐛 Troubleshooting

### ❌ Connection Refused
**Problem**: Can't connect to Railway database

**Fix**:
1. Get Railway PUBLIC host (not .railway.internal)
2. Check Railway MySQL has "Public Networking" enabled
3. Verify port number is correct

### ❌ CSRF Token Mismatch
**Fix**: Ensure SESSION_DRIVER=database and SANCTUM_STATEFUL_DOMAINS matches your Render URL

### ❌ Access Denied
**Fix**: Double-check database password is correct

---

## 💰 Cost
- **Render Web**: FREE (750 hours/month)
- **Railway Database**: FREE ( credit/month)
- **Total**: **/month** ✨

---

## 📚 Full Documentation
See **RENDER_WITH_RAILWAY_DATABASE.md** for complete guide.

---

**Ready to deploy? Follow steps 1-3 above!** 🚀
