# 🌙 Echoes & Brews

> "Where your thoughts find a quiet table, and your feelings find a cup of warmth."

A cozy journal café web platform for creative writing, journaling, and anonymous sharing.

## ✨ Features

### Core Features
- **Dual-Mode Writing System**
  - ☕ **Café Notes**: Public blog-style posts for sharing thoughts and creativity
  - 🌫️ **EchoMind**: Private/anonymous journal entries for personal reflection

- **Flexible Anonymity**
  - Post as yourself, username only, or completely anonymous
  - Privacy controls for each post

- **Mood Tracking**
  - Track emotional states with each entry
  - Visual mood calendar
  - Filter content by mood (Happy 🌤️, Melancholy 🌧️, Calm 🌙, Reflective 💭)

- **Community Engagement**
  - Reactions (❤️ Hearts, ☕ Coffee)
  - Comments on public posts
  - Explore and discover new writings

- **Cozy Lo-Fi Aesthetic**
  - Dark/Light theme toggle
  - Paper texture backgrounds
  - Handwritten font accents
  - Subtle animations

### Technical Features
- **Database Flexibility**: Switch between MySQL (production) and SQLite (testing) with a single config variable
- **Responsive Design**: Mobile-first approach
- **PWA Support**: Installable on any device
- **Admin Dashboard**: Complete control over users, posts, and site settings

## 🚀 Deployment to InfinityFree

### Requirements
- PHP 7.4+ (InfinityFree supports PHP 7.4, 8.0, 8.1)
- MySQL database
- File upload capability

### Step-by-Step Deployment

#### 1. Upload Files
Upload all project files to your InfinityFree hosting account via:
- FTP (FileZilla recommended)
- File Manager in cPanel

Place files in `htdocs` or `public_html` directory.

#### 2. Configure Database

**Edit `config/config.php`:**
```php
// Change DB_TYPE to 'mysql'
define('DB_TYPE', 'mysql');

// Update MySQL credentials from InfinityFree
define('DB_HOST', 'sql123.infinityfree.net'); // Your DB host
define('DB_NAME', 'epiz_12345678_echoes'); // Your database name
define('DB_USER', 'epiz_12345678'); // Your MySQL username
define('DB_PASS', 'your_password_here'); // Your MySQL password

// Update site URL
define('SITE_URL', 'https://yourdomain.infinityfreeapp.com');
```

#### 3. Create MySQL Database
1. Log into InfinityFree cPanel
2. Go to "MySQL Databases"
3. Create a new database
4. Note the credentials (host, database name, username, password)

#### 4. Initialize Database
1. Access `https://yourdomain.infinityfreeapp.com/init_db.php`
2. This will create all tables and default admin account
3. **Default Admin Credentials:**
   - Username: `admin`
   - Password: `admin123`
   - **⚠️ Change immediately after first login!**

#### 5. Set Permissions
Ensure the `uploads` directory is writable:
```bash
chmod 755 uploads
chmod 755 uploads/profiles
chmod 755 uploads/posts
```

#### 6. Security Steps
1. Delete or restrict access to `init_db.php` after initialization
2. Change admin password
3. Update `config/config.php` to disable error display:
   ```php
   error_reporting(0);
   ini_set('display_errors', 0);
   ```

### File Structure
```
echoes-brews/
├── config/
│   ├── config.php          # Main configuration
│   └── database.php        # Database abstraction
├── includes/
│   ├── header.php          # Site header
│   ├── footer.php          # Site footer
│   └── functions.php       # Helper functions
├── assets/
│   ├── css/style.css       # Main stylesheet
│   ├── js/main.js          # JavaScript functionality
│   └── images/             # Site images
├── admin/
│   └── index.php           # Admin dashboard
├── api/
│   ├── create_post.php     # Post creation
│   └── reaction.php        # Reaction handling
├── uploads/
│   ├── profiles/           # User avatars
│   └── posts/              # Post images
├── index.php               # Home page
├── cafe.php                # Café Notes page
├── echomind.php            # EchoMind Journal page
├── explore.php             # Explore page
├── about.php               # About page
├── contact.php             # Contact page
├── post.php                # Post detail view
├── register.php            # User registration
├── login.php               # User login
├── logout.php              # User logout
├── schema_mysql.sql        # MySQL schema
├── schema_sqlite.sql       # SQLite schema (for local testing)
└── init_db.php             # Database initialization
```

## 🔧 Local Testing (SQLite)

For local development and testing:

1. Ensure `config/config.php` has:
   ```php
   define('DB_TYPE', 'sqlite');
   ```

2. Run with PHP built-in server:
   ```bash
   php -S localhost:8000
   ```

3. Access `http://localhost:8000/init_db.php` to initialize SQLite database

4. Database file will be created as `database.sqlite` in root directory

## 🎨 Customization

### Theme Colors
Edit CSS custom properties in `assets/css/style.css`:
```css
:root {
    --light-bg: #F8F5F1;
    --dark-bg: #1E1B18;
    --accent-color: #CDAA7D;
    /* ... */
}
```

### Site Settings
Admin can customize via Dashboard:
- Site quote
- Daily quote
- Maintenance mode

### Google Fonts
Currently using:
- Playfair Display (headings)
- Inter (body text)
- Dancing Script (handwritten accents)

Update in `includes/header.php` if needed.

## 🛠️ Technologies

- **Backend**: PHP 8.x
- **Database**: MySQL / SQLite (switchable)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Fonts**: Google Fonts
- **Icons**: Unicode Emojis

## 📱 PWA Configuration

The site includes PWA support via `manifest.json`. To enable:
1. Ensure HTTPS is enabled (InfinityFree provides free SSL)
2. Add service worker if needed for offline functionality
3. Icons are referenced in `assets/images/` (add your own)

## 🔐 Security Features

- Password hashing with `password_hash()`
- Prepared statements (PDO) prevent SQL injection
- XSS protection via `htmlspecialchars()`
- Session security settings in `config/config.php`
- CSRF protection (recommended to add)

## 📧 Contact Form

To enable email sending:
1. Install PHPMailer: `composer require phpmailer/phpmailer`
2. Update `contact.php` with SMTP credentials
3. InfinityFree has email sending limits - check their docs

## 🐛 Troubleshooting

### Database Connection Errors
- Verify credentials in `config/config.php`
- Check database exists in cPanel
- Ensure MySQL service is running

### Upload Errors
- Check directory permissions (755 or 777)
- Verify InfinityFree upload limits
- Check PHP `upload_max_filesize` setting

### White Screen / 500 Error
- Check PHP error logs in cPanel
- Enable error display temporarily in config
- Verify PHP version compatibility

## 📄 License

This project is open source. Feel free to modify and use for your needs.

## 🙏 Credits

Created with ☕ and 🌙 for writers, dreamers, and thinkers everywhere.

---

**Need Help?** Visit the contact page or check InfinityFree documentation at https://infinityfree.net/support
