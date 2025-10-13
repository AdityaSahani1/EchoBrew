<?php
require_once __DIR__ . '/config/config.php';

// Initialize database based on DB_TYPE
try {
    if (DB_TYPE === 'sqlite') {
        // SQLite initialization
        $pdo = new PDO("sqlite:" . SQLITE_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = file_get_contents(__DIR__ . '/schema_sqlite.sql');
        $pdo->exec($sql);
        
        echo "✓ SQLite database initialized successfully!<br>";
        echo "Database file: " . SQLITE_PATH . "<br>";
        
    } elseif (DB_TYPE === 'mysql') {
        // MySQL initialization
        $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE " . DB_NAME);
        
        $sql = file_get_contents(__DIR__ . '/schema_mysql.sql');
        $pdo->exec($sql);
        
        echo "✓ MySQL database initialized successfully!<br>";
        echo "Database: " . DB_NAME . "<br>";
    }
    
    echo "<br>Default admin credentials:<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='index.php'>Go to Home Page</a>";
    
} catch (PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
