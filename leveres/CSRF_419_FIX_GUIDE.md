# 419 CSRF Error Fix for Railway Deployment

## Problem
Getting 419 Page Expired errors when creating drivers, vehicles, or making any POST requests on Railway.

## Root Causes
1. **Incorrect SESSION_DOMAIN** - Using .railway.app causes cookie issues
2. **Missing CSRF token in AJAX requests** - Some forms may not include the token
3. **Session not persisting** - Database sessions not working properly

## Solution: Update Railway Environment Variables

### STEP 1: Fix Session Domain
Go to Railway Dashboard ? Your Project ? Variables

**REMOVE or SET TO NULL:**
```
SESSION_DOMAIN=
```

**OR completely remove the SESSION_DOMAIN variable**

### STEP 2: Update These Variables (Keep existing)
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### STEP 3: Add CSRF Specific Settings
```
SESSION_HTTP_ONLY=true
SANCTUM_STATEFUL_DOMAINS=it12-prime-mover.up.railway.app
```

### STEP 4: Verify APP_URL
```
APP_URL=https://it12-prime-mover.up.railway.app
```

## Quick Fix Commands (Run in Railway CLI or SSH)

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure sessions table exists
php artisan migrate --force

# Generate new app key if needed
php artisan key:generate --force
```

## Testing After Fix

1. **Clear Browser Cache & Cookies** for railway.app domain
2. **Open Incognito/Private Window**
3. **Login again**
4. **Try creating a driver or vehicle**

## Expected Result
? Forms submit successfully
? No 419 errors
? CSRF token validation works

## If Still Having Issues

### Check 1: Verify CSRF Token in Forms
All forms should have:
```html
@csrf
```

### Check 2: Verify AJAX Requests Include Token
```javascript
fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
    },
    body: JSON.stringify(data)
})
```

### Check 3: Check Browser Console
- Open DevTools (F12)
- Look for CSRF token in request headers
- Check cookies for laravel_session

### Check 4: Verify Sessions Table
```sql
SELECT * FROM sessions LIMIT 5;
```

## Final Railway Environment Variables

```env
APP_NAME=\"IT12 Dispatch System\"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ZpA2I/aeKDjUsxjUUDILwLqqLxMGlmU9tqJZnTgdEB8=
APP_URL=https://it12-prime-mover.up.railway.app

DB_CONNECTION=mysql
DB_HOST=mysql.railway.internal
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=vkYuzamohnYfLsGrpeNdmqzRAGUuoaIj

CACHE_DRIVER=file
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
SESSION_HTTP_ONLY=true

QUEUE_CONNECTION=sync

SANCTUM_STATEFUL_DOMAINS=it12-prime-mover.up.railway.app
```

**Note: SESSION_DOMAIN should NOT be set!**

## Why This Fixes The Issue

1. **Removing SESSION_DOMAIN** - Allows cookies to work on the exact Railway domain
2. **SESSION_SECURE_COOKIE=true** - Required for HTTPS (Railway uses HTTPS)
3. **SESSION_SAME_SITE=lax** - Allows cookies to be sent with form submissions
4. **Database Sessions** - Persists across container restarts

