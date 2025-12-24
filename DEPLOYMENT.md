# SafarStep v2 Deployment Guide

## Database Setup

Your hosting provider should have provided MySQL credentials. Update the .env file with these values:

```env
DB_CONNECTION=mysql
DB_HOST=<your-db-host>
DB_PORT=3306
DB_DATABASE=<your-db-name>
DB_USERNAME=<your-db-user>
DB_PASSWORD=<your-db-password>
```

## Steps to Deploy

### 1. Create Database (if not done automatically)
Ask your host to create a MySQL database and user with the credentials shown above.

### 2. Update Environment
Edit `/v2/.env` with the MySQL credentials:
```bash
cd /home/safarstep/public_html/v2
# Edit .env with DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 3. Run Migrations and Seeders
```bash
php composer.phar migrate --force
php artisan db:seed --force
```

### 4. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
```

### 5. Verify Deployment
- Test the API: `https://safarstep.com/v2/api/v1/auth/register` (POST)
- Check dashboard: `https://safarstep.com/v2/dashboard`

## Database Credentials to Request from Host

When contacting your hosting provider, request:
- MySQL database name
- MySQL username
- MySQL password
- MySQL host (usually `localhost` on shared hosting)
- MySQL port (usually `3306`)

## Seeded Data

After running `php artisan db:seed`, the database will include:
- **Permissions**: 73 permissions across 14 modules
- **Roles**: super_admin, admin, manager, employee
- **Currencies**: USD, EUR, GBP, AED, SAR, EGP, JOD, KWD
- **Tenants**: 2 sample tenants with branding colors
- **Users**: 1 test user (from UserFactory in DatabaseSeeder)

## Environment Configuration

Current .env settings:
- APP_NAME=SafarStep
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://safarstep.com/v2
- SESSION_DRIVER=database (uses sessions table)
- CACHE_DRIVER=database (uses cache table)
- QUEUE_CONNECTION=database (uses jobs table)

All drivers use the database when Redis is unavailable. For better performance, ask your host about Redis availability.

## Support

If you encounter issues:
1. Check storage/logs/laravel.log for errors
2. Verify database credentials in .env
3. Ensure database user has CREATE, ALTER, DROP, SELECT, INSERT, UPDATE, DELETE privileges
4. Contact your host for permission issues or configuration questions
