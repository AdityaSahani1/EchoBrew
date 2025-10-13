# 🚀 Deployment Guide for InfinityFree

## Quick Start Checklist

- [ ] Upload files to hosting
- [ ] Create MySQL database
- [ ] Update config.php with database credentials
- [ ] Run init_db.php to initialize database
- [ ] Change admin password
- [ ] Set upload folder permissions
- [ ] Delete init_db.php
- [ ] Enable HTTPS/SSL

## Detailed Steps

### 1. Get InfinityFree Account
1. Sign up at https://infinityfree.net
2. Create a new account/website
3. Note your cPanel credentials

### 2. Access File Manager or FTP

**Option A: File Manager (Easier)**
1. Log into InfinityFree cPanel
2. Open "Online File Manager"
3. Navigate to `htdocs` folder
4. Upload all project files

**Option B: FTP (Recommended)**
1. Download FileZilla: https://filezilla-project.org/
2. Get FTP credentials from InfinityFree:
   - Host: `ftpupload.net` or `ftp.yourdomain.com`
   - Username: `epiz_12345678`
   - Password: (from InfinityFree panel)
   - Port: `21`
3. Connect and upload all files to `htdocs`

### 3. Create MySQL Database

1. In cPanel, go to "MySQL Databases"
2. Click "Create Database"
3. Name it (e.g., `echoes_brews`)
4. Save the database name shown (e.g., `epiz_12345678_echoes`)
5. Note the MySQL credentials:
   - **Host**: Usually `sql123.infinityfree.net`
   - **Database**: `epiz_12345678_echoes`
   - **Username**: `epiz_12345678`
   - **Password**: (your MySQL password)

### 4. Configure Application

⚠️ **CRITICAL**: You MUST change DB_TYPE before deployment!

Edit `config/config.php`:

```php
<?php
// Database Configuration
define('DB_TYPE', 'mysql'); // ⚠️ MUST CHANGE FROM 'sqlite' to 'mysql' for InfinityFree!

// MySQL Configuration - UPDATE THESE!
define('DB_HOST', 'sql123.infinityfree.net'); // Your MySQL host
define('DB_NAME', 'epiz_12345678_echoes');    // Your database name
define('DB_USER', 'epiz_12345678');            // Your MySQL username
define('DB_PASS', 'your_mysql_password');      // Your MySQL password

// Site Configuration - UPDATE!
define('SITE_URL', 'https://yourdomain.infinityfreeapp.com');

// ... rest of config
```

### 5. Initialize Database

1. Access: `https://yourdomain.infinityfreeapp.com/init_db.php`
2. You should see success message
3. Default admin login created:
   - Username: `admin`
   - Password: `admin123`

### 6. First Login & Security

1. Go to: `https://yourdomain.infinityfreeapp.com/login.php`
2. Login with admin credentials
3. **IMMEDIATELY** change password:
   - Go to Profile/Settings
   - Update password
   - Update email

4. **Delete initialization file**:
   - Via File Manager or FTP, delete `init_db.php`
   - Or rename it to `init_db.php.bak`

### 7. Set Folder Permissions

Via File Manager or FTP, set permissions for upload folders:

```
uploads/          -> 755
uploads/profiles/ -> 755
uploads/posts/    -> 755
```

If uploads fail, try `777` but revert to `755` after testing.

### 8. Production Optimization

Update `config/config.php`:

```php
// Disable error display in production
error_reporting(0);
ini_set('display_errors', 0);

// Enable HTTPS
ini_set('session.cookie_secure', 1);
```

### 9. Enable SSL (HTTPS)

1. In InfinityFree cPanel, go to "SSL Certificates"
2. Install free SSL certificate
3. Wait 5-10 minutes for activation
4. Update `SITE_URL` in config to use `https://`

### 10. Test Everything

- [ ] Home page loads
- [ ] Register new account
- [ ] Login works
- [ ] Create café note
- [ ] Create echo entry
- [ ] Upload profile picture
- [ ] Reactions work
- [ ] Comments work
- [ ] Admin dashboard accessible
- [ ] Mood tracker displays

## InfinityFree Limitations

Be aware of InfinityFree free tier limits:

| Resource | Limit |
|----------|-------|
| Disk Space | 5 GB |
| Bandwidth | Unlimited |
| Databases | 400 |
| Email Accounts | Unlimited |
| Hits per Day | 50,000 |
| PHP Memory | 128 MB |
| Execution Time | 30 seconds |
| Upload Size | 10 MB |

## Troubleshooting

### "Database connection failed"
- Verify credentials in `config/config.php`
- Check database exists in MySQL panel
- Try using IP instead of hostname

### "Parse error" or "Syntax error"
- Check PHP version (should be 7.4+)
- Look for missing semicolons or brackets
- Enable error display temporarily

### "Permission denied" on uploads
- Set folder permissions to 755 or 777
- Check if `uploads` folder exists
- Verify PHP has write permissions

### Images/CSS not loading
- Check file paths (case-sensitive on Linux)
- Verify `.htaccess` allows file types
- Check SITE_URL in config is correct

### Email not sending
- Use SMTP instead of mail() function
- Check InfinityFree email limits
- Consider external SMTP (Gmail, SendGrid)

## Custom Domain Setup

1. Register domain (Namecheap, GoDaddy, etc.)
2. In InfinityFree, go to "Addon Domains"
3. Add your custom domain
4. Update DNS nameservers at domain registrar:
   ```
   ns1.byet.org
   ns2.byet.org
   ns3.byet.org
   ns4.byet.org
   ns5.byet.org
   ```
5. Wait 24-48 hours for DNS propagation
6. Update `SITE_URL` in config.php

## Backup Strategy

**Regular Backups:**
1. Download entire `htdocs` folder via FTP weekly
2. Export MySQL database from phpMyAdmin
3. Store backups securely off-server

**Database Backup:**
1. Open phpMyAdmin in cPanel
2. Select your database
3. Click "Export" tab
4. Choose "Quick" method
5. Download .sql file

## Performance Tips

1. **Enable Gzip Compression**
   - Add to `.htaccess`:
   ```apache
   <IfModule mod_deflate.c>
     AddOutputFilterByType DEFLATE text/html text/css text/javascript
   </IfModule>
   ```

2. **Browser Caching**
   - Add to `.htaccess`:
   ```apache
   <IfModule mod_expires.c>
     ExpiresActive On
     ExpiresByType image/jpg "access 1 year"
     ExpiresByType image/jpeg "access 1 year"
     ExpiresByType image/png "access 1 year"
     ExpiresByType text/css "access 1 month"
     ExpiresByType application/javascript "access 1 month"
   </IfModule>
   ```

3. **Optimize Images**
   - Compress images before upload
   - Use WebP format when possible
   - Set max upload dimensions

4. **Database Optimization**
   - Add indexes to frequently queried columns
   - Archive old posts periodically
   - Clean up orphaned records

## Migration from SQLite to MySQL

If you tested locally with SQLite:

1. Export SQLite data:
   ```bash
   sqlite3 database.sqlite .dump > data.sql
   ```

2. Convert to MySQL format (fix syntax)
3. Import to MySQL via phpMyAdmin
4. Update `config.php` DB_TYPE to 'mysql'

## Getting Help

- InfinityFree Forum: https://forum.infinityfree.net
- InfinityFree Support: Submit ticket in cPanel
- Check PHP error logs in cPanel
- Review README.md for additional info

## Next Steps After Deployment

- [ ] Customize site branding (logo, colors)
- [ ] Set up analytics (Google Analytics)
- [ ] Configure contact form email
- [ ] Create content guidelines
- [ ] Invite beta users
- [ ] Set up regular backups
- [ ] Monitor performance
- [ ] Plan for scaling if needed

---

**Deployed Successfully?** 🎉
Share your Echoes & Brews café with the world!
