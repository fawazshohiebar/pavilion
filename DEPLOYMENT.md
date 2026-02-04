# 🚀 Coolify Deployment Guide for Pavilion

This guide will help you deploy the Pavilion Laravel/Statamic application to Coolify.

## 📋 Prerequisites

- Coolify server set up and running
- GitHub repository with this code
- Domain name (optional but recommended)

## 🔧 Coolify Configuration

### Step 1: Create New Application

1. Log in to your Coolify dashboard
2. Go to **Projects** → Click on your project (or create new)
3. Click **Add New Application**

### Step 2: Repository Configuration

Fill in the following details:

| Setting | Value |
|---------|-------|
| **Repository URL** | `https://github.com/fawazshoheibar/pavilion` |
| **Branch** | `main` (or your production branch) |
| **Build Pack** | **Dockerfile** (not Nixpacks) |
| **Base Directory** | `/` |
| **Dockerfile Location** | `Dockerfile` |
| **Port** | `80` |
| **Is it a static site?** | ❌ No |

### Step 3: Environment Variables

Add these environment variables in Coolify:

#### Required Variables

```bash
APP_NAME=Pavilion
APP_ENV=production
APP_KEY=base64:GENERATE_THIS_WITH_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=pavilion
DB_USERNAME=pavilion_user
DB_PASSWORD=YOUR_SECURE_PASSWORD_HERE

# Session & Cache
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database

# Statamic
STATAMIC_LICENSE_KEY=your_license_key_here
STATAMIC_STACHE_WATCHER=false
STATAMIC_STATIC_CACHING_STRATEGY=half
```

#### Optional Variables (Recommended)

```bash
# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Turnstile Captcha (if you're using it)
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

### Step 4: Add Database Service (if not using external DB)

1. In Coolify, go to your project
2. Click **Add Service**
3. Select **MySQL 8.0**
4. Configure:
   - Name: `mysql`
   - Root Password: (generate secure password)
   - Database: `pavilion`
   - Username: `pavilion_user`
   - Password: (generate secure password)
5. Use these credentials in your environment variables

### Step 5: Domain Configuration (Optional)

1. Go to application settings in Coolify
2. Add your domain: `yourdomain.com`
3. Enable SSL/TLS (Let's Encrypt)
4. Point your domain DNS A record to Coolify server IP

### Step 6: Deploy

1. Click **Deploy** button
2. Monitor build logs
3. Wait for deployment to complete (3-5 minutes)

## 🔨 Post-Deployment Tasks

After the first successful deployment, execute these commands via Coolify terminal:

### Option A: Using Coolify Terminal

```bash
# Generate application key (if not set)
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Create storage symlink
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear Statamic caches
php artisan statamic:stache:clear
php artisan statamic:static:clear
```

### Option B: Run Commands from Service

In Coolify, go to your application → **Execute Command**:

```bash
sh -c "php artisan migrate --force && php artisan storage:link && php artisan config:cache"
```

## 📁 File Structure

This deployment uses the following Docker files:

- `Dockerfile` - Main production Dockerfile (multi-stage build)
- `docker-compose.coolify.yaml` - Optimized for Coolify
- `docker/nginx.prod.Dockerfile` - Nginx configuration
- `docker/php.Dockerfile` - PHP-FPM configuration
- `docker/nginx.default.conf` - Nginx server configuration
- `docker/php-production.ini` - PHP production settings
- `docker/supervisord.conf` - Supervisor to manage processes
- `.dockerignore` - Files to exclude from Docker build

## 🔍 Troubleshooting

### Build Fails

1. Check build logs in Coolify
2. Ensure all required files are committed to GitHub
3. Verify Dockerfile syntax

### Database Connection Errors

1. Verify database service is running
2. Check DB credentials in environment variables
3. Ensure DB_HOST matches your database service name

### Permission Errors

The Docker setup automatically handles permissions, but if you encounter issues:

```bash
chown -R laravel:laravel /var/www/html/storage
chmod -R 775 /var/www/html/storage
```

### Assets Not Loading

1. Check if build ran successfully
2. Verify `npm run build` completed during Docker build
3. Check nginx configuration

### Application Key Error

Generate a new key:

```bash
php artisan key:generate --force
```

Then add the generated key to environment variables.

## 🔐 Security Checklist

- [ ] APP_DEBUG is set to `false`
- [ ] APP_ENV is set to `production`
- [ ] Strong DB passwords are used
- [ ] APP_KEY is generated and secure
- [ ] SSL/TLS is enabled
- [ ] `.env` file is not committed to Git
- [ ] File permissions are correct (775 for storage)
- [ ] Only necessary ports are exposed

## 📊 Monitoring

1. Check application logs in Coolify dashboard
2. Monitor resource usage (CPU, Memory)
3. Set up health check notifications
4. Review Laravel logs: `storage/logs/laravel.log`

## 🔄 Updating Your Application

To deploy updates:

1. Push changes to GitHub repository
2. In Coolify, click **Redeploy** button
3. Monitor build logs
4. Verify deployment was successful

## 📞 Support

If you encounter issues:

1. Check Coolify logs
2. Review Docker build logs
3. Verify all environment variables
4. Check Laravel logs in storage/logs
5. Test database connectivity

## 🎯 Production Optimizations

This deployment includes:

- ✅ Multi-stage Docker build (smaller image)
- ✅ OPcache enabled for PHP
- ✅ Asset compilation during build
- ✅ Composer autoload optimization
- ✅ Configuration caching
- ✅ Health checks
- ✅ Supervisor for process management
- ✅ Nginx + PHP-FPM in single container
- ✅ Proper file permissions
- ✅ Security hardening

## 📝 Notes

- The main Dockerfile combines Nginx and PHP-FPM using Supervisor
- Assets are built during Docker build process (no need for runtime compilation)
- Storage and cache directories use Docker volumes for persistence
- The application runs on port 80 by default
- Health checks are configured at `/health` endpoint

---

**Ready to deploy!** 🚀 Follow the steps above and your application will be live on Coolify.
