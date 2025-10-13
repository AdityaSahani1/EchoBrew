<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'cafe';
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $mood = $_POST['mood'] ?? 'calm';
    $visibility = $_POST['visibility'] ?? 'public';
    $anonymity = $_POST['anonymity'] ?? 'username';
    
    if (empty($content)) {
        $_SESSION['error'] = 'Content is required';
        header('Location: ../' . ($type === 'cafe' ? 'cafe.php?new=1' : 'echomind.php?new=1'));
        exit;
    }
    
    // Validate enums
    $valid_moods = ['happy', 'melancholy', 'calm', 'reflective', 'other'];
    $valid_anonymity = ['anonymous', 'username', 'fullname'];
    
    if (!in_array($mood, $valid_moods)) $mood = 'calm';
    if (!in_array($anonymity, $valid_anonymity)) $anonymity = 'username';
    
    $db = Database::getInstance();
    
    $db->query(
        "INSERT INTO posts (user_id, title, content, mood, type, visibility, anonymity) 
         VALUES (?, ?, ?, ?, ?, ?, ?)",
        [$_SESSION['user_id'], $title, $content, $mood, $type, $visibility, $anonymity]
    );
    
    // Update mood tracker
    $today = date('Y-m-d');
    if (DB_TYPE === 'mysql') {
        $db->query(
            "INSERT INTO moods (user_id, date, mood_type) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE mood_type = ?, updated_at = NOW()",
            [$_SESSION['user_id'], $today, $mood, $mood]
        );
    } else {
        $db->query(
            "INSERT OR REPLACE INTO moods (user_id, date, mood_type) VALUES (?, ?, ?)",
            [$_SESSION['user_id'], $today, $mood]
        );
    }
    
    $_SESSION['success'] = 'Post created successfully!';
    header('Location: ../' . ($type === 'cafe' ? 'cafe.php' : 'echomind.php'));
    exit;
}

header('Location: ../index.php');
exit;
