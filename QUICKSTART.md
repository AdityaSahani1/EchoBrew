# 🚀 Quick Start Guide - Echoes & Brews

## For InfinityFree Deployment (Production)

### 1. Upload & Configure (10 minutes)
```bash
1. Upload all files to htdocs folder via FTP or File Manager
2. Edit config/config.php:
   - Set DB_TYPE = 'mysql'
   - Add your MySQL credentials from InfinityFree
   - Update SITE_URL to your domain
3. Create MySQL database in cPanel
4. Visit: https://yourdomain.com/init_db.php
5. Login with admin/admin123
6. Change password immediately
7. Delete init_db.php
```

### 2. Default Admin Account
- **Username**: `admin`
- **Password**: `admin123`
- ⚠️ **CHANGE THIS IMMEDIATELY AFTER FIRST LOGIN**

### 3. Key Files to Configure
- `config/config.php` - Database and site settings
- `.htaccess` - Enable HTTPS redirect after SSL is active
- `uploads/` folders - Ensure writable (755 or 777)

## For Local Testing (SQLite)

### 1. Requirements
- PHP 7.4+ installed locally
- Command line access

### 2. Run Locally
```bash
# The config is already set to SQLite by default
php -S localhost:8000

# Visit: http://localhost:8000/init_db.php
# Then: http://localhost:8000
```

### 3. Switch to MySQL Later
Just change `DB_TYPE = 'sqlite'` to `'mysql'` in config.php

## 📚 Full Documentation

- **README.md** - Complete feature list and overview
- **DEPLOYMENT.md** - Step-by-step InfinityFree deployment guide
- **assets/images/README.md** - Image requirements

## 🎨 Customization

### Change Colors
Edit `assets/css/style.css`:
```css
:root {
    --accent-color: #CDAA7D; /* Change this */
    --dark-bg: #1E1B18;      /* And this */
}
```

### Update Site Quote
Login as admin → Dashboard → Settings → Update quotes

### Add Logo/Favicon
Place images in `assets/images/`:
- `favicon.png` (32x32 or 64x64)
- `default-avatar.png` (200x200)
- `icon-192.png` and `icon-512.png` for PWA

## 🔒 Security Checklist

- [ ] Changed admin password
- [ ] Deleted init_db.php
- [ ] Set DB_TYPE to 'mysql' for production
- [ ] Enabled HTTPS/SSL
- [ ] Set upload folder permissions
- [ ] Disabled error display in config
- [ ] Updated SITE_URL to production domain

## 📁 Project Structure

```
echoes-brews/
├── config/          # Configuration files
├── includes/        # Shared PHP includes
├── assets/          # CSS, JS, images
├── admin/           # Admin dashboard
├── api/             # AJAX endpoints
├── uploads/         # User uploads
├── index.php        # Home page
├── cafe.php         # Public posts
├── echomind.php     # Private journal
├── explore.php      # Discovery
├── about.php        # About page
├── contact.php      # Contact form
└── post.php         # Single post view
```

## 🆘 Quick Troubleshooting

**Database error?**
→ Check credentials in config.php

**Uploads failing?**
→ Set uploads/ folders to 755 or 777

**White screen?**
→ Enable error display in config temporarily

**Theme not working?**
→ Clear browser cache, check JavaScript console

## 🌟 Next Steps

1. Customize colors and branding
2. Add your logo and favicon
3. Write welcome posts
4. Invite users to join
5. Set up regular backups

---

**Need detailed help?** See DEPLOYMENT.md for complete instructions.
