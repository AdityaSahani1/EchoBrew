<?php
require_once __DIR__ . '/functions.php';
$current_user = getCurrentUser();
$page_title = $page_title ?? 'Echoes & Brews';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($page_title) ?> - Echoes & Brews</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&family=Dancing+Script:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#1E1B18">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.png" type="image/png">
</head>
<body class="<?= $_COOKIE['theme'] ?? 'dark' ?>">
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-brand">
                <a href="index.php" class="logo">
                    <span class="logo-text">Echoes & Brews</span>
                    <span class="logo-subtitle">☕</span>
                </a>
            </div>
            
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="cafe.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cafe.php' ? 'active' : '' ?>">Café</a></li>
                <li><a href="echomind.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'echomind.php' ? 'active' : '' ?>">EchoMind</a></li>
                <li><a href="explore.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'explore.php' ? 'active' : '' ?>">Explore</a></li>
                <li><a href="about.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About</a></li>
                <li><a href="contact.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            </ul>
            
            <div class="nav-actions">
                <button id="theme-toggle" class="theme-btn" aria-label="Toggle theme">
                    <span class="sun-icon">☀️</span>
                    <span class="moon-icon">🌙</span>
                </button>
                
                <?php if ($current_user): ?>
                    <?php if (isAdmin()): ?>
                        <a href="admin/index.php" class="btn-admin">Admin</a>
                    <?php endif; ?>
                    <div class="user-menu">
                        <button class="user-avatar">
                            <img src="uploads/profiles/<?= escape($current_user['profile_pic']) ?>" alt="Profile" onerror="this.src='assets/images/default-avatar.png'">
                        </button>
                        <div class="dropdown-menu">
                            <a href="profile.php">Profile</a>
                            <a href="settings.php">Settings</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-primary">Login</a>
                <?php endif; ?>
                
                <button class="mobile-menu-toggle">☰</button>
            </div>
        </nav>
    </header>
    
    <main class="main-content">
