# Coolify Deployment Guide for Pavilion (Laravel/Statamic)

## Important: Fix These in Coolify BEFORE Deploying

### 1. Fix APP_KEY (CRITICAL)
Your current APP_KEY has a duplicate `base64:` prefix. It should be:

**WRONG:**
```
APP_KEY=base64:base64:uOvh9Dvm8O0hVgxvcWVU++258E2SnPQqS8m6fsNhke0=
```

**CORRECT:**
```
APP_KEY=base64:uOvh9Dvm8O0hVgxvcWVU++258E2SnPQqS8m6fsNhke0=
```

### 2. Required Environment Variables

Set these in Coolify's Environment Variables section:

```bash
# Application
APP_NAME=Pavilion
APP_ENV=production
APP_KEY=base64:uOvh9Dvm8O0hVgxvcWVU++258E2SnPQqS8m6fsNhke0=
APP_DEBUG=false
APP_URL=http://ooks4oc84k0sggsg4ksggkkg.13.50.242.49.sslip.io

# Database (SQLite)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# Statamic (if you have a license)
STATAMIC_LICENSE_KEY=your-license-key-here
```

### 3. Post-Deployment Commands

In Coolify, set these post-deployment commands:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan statamic:static:clear
php artisan statamic:stache:refresh
```

**Note:** Do NOT run `php artisan migrate` or `composer install` here - those are handled in the Dockerfile.

### 4. Build Configuration

- **Build Pack:** Dockerfile
- **Dockerfile Location:** `Dockerfile`
- **Port:** 80
- **Health Check:** Enabled (already configured in Dockerfile)

### 5. Troubleshooting 500 Errors

If you're getting HTTP 500 errors after deployment:

#### Check Container Logs
In Coolify, click on "Show Logs" to see PHP errors.

#### Common Issues:

1. **Storage Permissions:**
   - The Dockerfile already sets up permissions, but if issues persist, add this to post-deployment commands:
   ```bash
   chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
   ```

2. **Missing APP_KEY:**
   - Ensure APP_KEY is set correctly (without duplicate `base64:`)

3. **SQLite Database:**
   - The database file is automatically created, but ensure `DB_DATABASE` path is correct

4. **Statamic License:**
   - If you see Statamic license errors, either add your license key or disable CP in production

#### View Live Logs:
```bash
# In Coolify, use the "Logs" tab or SSH into the server:
docker logs -f <container-name>
```

### 6. SSL/HTTPS Setup (Recommended)

Once the app is working on HTTP, enable SSL in Coolify:
1. Go to your application settings
2. Enable "SSL/TLS"
3. Coolify will automatically provision Let's Encrypt certificate
4. Update `APP_URL` to `https://...`

### 7. Performance Optimization

After deployment works, consider:
- Using Redis instead of file cache (`CACHE_STORE=redis`)
- Using Redis for sessions (`SESSION_DRIVER=redis`)
- Using database queue instead of sync (`QUEUE_CONNECTION=database`)
- Enable OPcache (already configured in `docker/php-production.ini`)

## Deployment Checklist

- [ ] Fix APP_KEY (remove duplicate `base64:`)
- [ ] Set APP_URL to your domain
- [ ] Set APP_DEBUG=false
- [ ] Configure database (SQLite path or MySQL credentials)
- [ ] Add Statamic license key (if applicable)
- [ ] Set correct post-deployment commands
- [ ] Verify healthcheck passes
- [ ] Test the application
- [ ] Enable SSL/HTTPS
- [ ] Monitor logs for any errors

## Need Help?

If you're still seeing errors:
1. Check the container logs in Coolify
2. Look for PHP errors in `/var/www/html/storage/logs/laravel.log`
3. Verify all environment variables are set correctly
4. Ensure the healthcheck is passing
