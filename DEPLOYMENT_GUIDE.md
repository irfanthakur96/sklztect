# WordPress Deployment Guide for Dokploy

## Issues Fixed

### 1. Apache ServerName Warning
- **Problem**: Apache couldn't determine the server's FQDN
- **Solution**: Added ServerName configuration to docker-compose.yml

### 2. WordPress Configuration
- **Problem**: No wp-config.php file existed
- **Solution**: Created proper wp-config.php with environment variable support

## Environment Variables Setup

### Automatic Domain Detection (Recommended)

The setup now automatically detects the domain from Dokploy. You only need to set:

```env
# Database Configuration
DB_NAME=wordpress
DB_USER=wordpress
DB_PASSWORD=your_secure_password_here

# WordPress Configuration
WORDPRESS_DEBUG=0
WORDPRESS_TABLE_PREFIX=1757397954_wp_
```

### Manual Domain Override (Optional)

If you want to override the automatic detection:

```env
# Domain Configuration (optional)
DOMAIN_NAME=your-custom-domain.com
WORDPRESS_HOME=https://your-custom-domain.com
WORDPRESS_SITEURL=https://your-custom-domain.com
```

### Database Import

After deployment, you need to manually import your database:

1. **Access phpMyAdmin**: Go to `https://phpmyadmin.sklztect-sklztectwordpress-s6acc6-a14060-213-210-37-251.traefik.me`
2. **Login**: Use your database credentials
3. **Select Database**: Choose the `wordpress` database
4. **Import**: Go to Import tab and upload your `combined_database.sql` file
5. **Click Go**: Wait for import to complete

## Security Recommendations

1. **Generate WordPress Security Keys**:
   - Visit: https://api.wordpress.org/secret-key/1.1/salt/
   - Replace the placeholder keys in wp-config.php

2. **Use Strong Database Passwords**:
   - Change the default password in your .env file
   - Use a password manager to generate secure passwords

3. **Configure Domain URLs**:
   - Set DOMAIN_NAME, WORDPRESS_HOME, and WORDPRESS_SITEURL in your .env file
   - These will automatically configure WordPress URLs and Traefik routing

## Deployment Steps

1. **Set environment variables** in Dokploy (database credentials only)
2. **Deploy your application** in Dokploy
3. **Access phpMyAdmin** to import your database
4. **Import combined_database.sql** via phpMyAdmin interface
5. **Access your WordPress site** - it should work now!

## Troubleshooting

If you encounter issues:

1. **Bad Gateway Error**: Usually means database is not imported yet
2. **Database Connection Error**: Check your database credentials in environment variables
3. **Domain Issues**: Verify your Traefik URL is correct
4. **Import Issues**: Make sure combined_database.sql file is valid and not corrupted

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
