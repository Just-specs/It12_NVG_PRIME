# Quick Reference: Render Deployment for IT12 Dispatch System

## 🎯 Quick Steps

### 1. Create Files (Already Done ✅)
- render.yaml
- render-build.sh
- .env.render (template)
- RENDER_DEPLOYMENT_GUIDE.md

### 2. Push to GitHub
```bash
git add .
git commit -m "Add Render deployment configuration"
git push origin main
```

### 3. On Render.com
1. Sign up at https://render.com
2. Create MySQL Database first
3. Create Web Service
4. Connect GitHub repo
5. Add environment variables
6. Deploy!

## 🔑 Important Environment Variables

Copy these to Render's Environment section:

```
APP_NAME=IT12 Dispatch System
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ZpA2I/aeKDjUsxjUUDILwLqqLxMGlmU9tqJZnTgdEB8=
APP_URL=https://YOUR-APP.onrender.com

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOST
DB_PORT=3306
DB_DATABASE=it12_dispatch
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=YOUR-APP.onrender.com
```

## 📝 Before Deployment Checklist

- [ ] Files created (render.yaml, render-build.sh)
- [ ] Git repository initialized
- [ ] Pushed to GitHub
- [ ] Render account created
- [ ] Database created on Render
- [ ] Database credentials saved
- [ ] Environment variables ready

## 🚀 Deploy Command
On Render, set:
- **Build Command**: ./render-build.sh
- **Start Command**: php artisan serve --host=0.0.0.0 --port=\

## 🐛 Troubleshooting

**Build fails?**
- Make render-build.sh executable: git update-index --chmod=+x render-build.sh

**CSRF errors?**
- Check SESSION_DRIVER=database
- Check SANCTUM_STATEFUL_DOMAINS matches your domain

**Database connection fails?**
- Verify database credentials in environment variables
- Use internal database URL if same region

## 📚 Full Documentation
See RENDER_DEPLOYMENT_GUIDE.md for complete instructions.

---
*Ready to deploy? Follow the steps above!*
