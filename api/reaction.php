<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? 0;
$type = $data['type'] ?? '';

if (!$post_id || !in_array($type, ['heart', 'coffee', 'comment'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

$db = Database::getInstance();

// Check if already reacted
$existing = $db->fetchOne(
    "SELECT id FROM reactions WHERE post_id = ? AND user_id = ? AND type = ?",
    [$post_id, $_SESSION['user_id'], $type]
);

if ($existing) {
    // Remove reaction
    $db->query(
        "DELETE FROM reactions WHERE id = ?",
        [$existing['id']]
    );
    $reacted = false;
} else {
    // Add reaction
    $db->query(
        "INSERT INTO reactions (post_id, user_id, type) VALUES (?, ?, ?)",
        [$post_id, $_SESSION['user_id'], $type]
    );
    $reacted = true;
}

// Get new count
$count = getReactionCount($post_id, $type);

echo json_encode([
    'success' => true,
    'reacted' => $reacted,
    'count' => $count
]);
