<?php
require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = Database::getInstance();
    return $db->fetchOne(
        "SELECT id, username, email, name, profile_pic, bio, role FROM users WHERE id = ?",
        [$_SESSION['user_id']]
    );
}

function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return "just now";
    if ($diff < 3600) return floor($diff / 60) . " min ago";
    if ($diff < 86400) return floor($diff / 3600) . " hr ago";
    if ($diff < 604800) return floor($diff / 86400) . " days ago";
    return date('M j, Y', $timestamp);
}

function getAuthorDisplay($post) {
    if ($post['anonymity'] === 'anonymous') {
        return 'Anonymous';
    } elseif ($post['anonymity'] === 'username') {
        return '@' . $post['username'];
    } else {
        return $post['name'] ?: $post['username'];
    }
}

function getMoodEmoji($mood) {
    $emojis = [
        'happy' => '🌤️',
        'melancholy' => '🌧️',
        'calm' => '🌙',
        'reflective' => '💭',
        'other' => '✨'
    ];
    return $emojis[$mood] ?? '✨';
}

function getMoodColor($mood) {
    $colors = [
        'happy' => '#FFD93D',
        'melancholy' => '#6C91BF',
        'calm' => '#8B7355',
        'reflective' => '#A084CA',
        'other' => '#CDAA7D'
    ];
    return $colors[$mood] ?? '#CDAA7D';
}

function getReactionCount($post_id, $type) {
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT COUNT(*) as count FROM reactions WHERE post_id = ? AND type = ?",
        [$post_id, $type]
    );
    return $result['count'] ?? 0;
}

function hasUserReacted($post_id, $type) {
    if (!isLoggedIn()) return false;
    
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT id FROM reactions WHERE post_id = ? AND user_id = ? AND type = ?",
        [$post_id, $_SESSION['user_id'], $type]
    );
    return $result !== false;
}

function uploadImage($file, $folder = 'posts') {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }
    
    $newname = uniqid() . '.' . $ext;
    $destination = UPLOAD_PATH . $folder . '/' . $newname;
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'filename' => $newname];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

function getSetting($key, $default = '') {
    $db = Database::getInstance();
    $result = $db->fetchOne(
        "SELECT setting_value FROM settings WHERE setting_key = ?",
        [$key]
    );
    return $result ? $result['setting_value'] : $default;
}

function updateSetting($key, $value) {
    $db = Database::getInstance();
    $db->query(
        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) 
         ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()",
        [$key, $value, $value]
    );
}
