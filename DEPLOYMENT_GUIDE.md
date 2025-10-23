# WordPress Deployment Guide for Dokploy

## Issues Fixed

### 1. Apache ServerName Warning
- **Problem**: Apache couldn't determine the server's FQDN
- **Solution**: Added ServerName configuration to docker-compose.yml

### 2. WordPress Configuration
- **Problem**: No wp-config.php file existed
- **Solution**: Created proper wp-config.php with environment variable support

## Environment Variables Setup

Create a `.env` file in your project root with the following variables:

```env
# Database Configuration
DB_NAME=wordpress
DB_USER=wordpress
DB_PASSWORD=your_secure_password_here

# WordPress Configuration
WORDPRESS_DEBUG=0
WORDPRESS_TABLE_PREFIX=1757397954_wp_
```

## Security Recommendations

1. **Generate WordPress Security Keys**:
   - Visit: https://api.wordpress.org/secret-key/1.1/salt/
   - Replace the placeholder keys in wp-config.php

2. **Use Strong Database Passwords**:
   - Change the default password in your .env file
   - Use a password manager to generate secure passwords

3. **Update WordPress URLs**:
   - The wp-config.php is configured for sklztect.com
   - Update WP_HOME and WP_SITEURL if your domain is different

## Deployment Steps

1. **Update your .env file** with secure passwords
2. **Generate WordPress security keys** and update wp-config.php
3. **Redeploy your application** in Dokploy
4. **Import your database** using the combined_database.sql file

## Database Import

To import your existing database:

1. Access phpMyAdmin at: https://phpmyadmin.sklztect.com
2. Select the 'wordpress' database
3. Go to Import tab
4. Upload your combined_database.sql file
5. Click Go to import

## Monitoring

- **Application Logs**: Check Dokploy logs for any remaining issues
- **Database Logs**: MySQL logs show successful initialization
- **Apache Logs**: ServerName warnings should be resolved

## Troubleshooting

If you encounter issues:

1. Check that all environment variables are set correctly
2. Verify database connection in wp-config.php
3. Ensure your domain DNS points to your VPS
4. Check Traefik routing configuration in docker-compose.yml
