<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$post_id = $_GET['id'] ?? 0;

if (!$post_id) {
    header('Location: index.php');
    exit;
}

$db = Database::getInstance();

$post = $db->fetchOne(
    "SELECT p.*, u.username, u.name, u.profile_pic 
     FROM posts p 
     JOIN users u ON p.user_id = u.id 
     WHERE p.id = ?",
    [$post_id]
);

if (!$post) {
    header('Location: index.php');
    exit;
}

// Check privacy BEFORE loading header
if ($post['visibility'] === 'private' && (!isLoggedIn() || $post['user_id'] != $_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$page_title = $post['title'] ?: 'Post';
require_once 'includes/header.php';

// Get comments
$comments = $db->fetchAll(
    "SELECT c.*, u.username, u.name, u.profile_pic 
     FROM comments c 
     JOIN users u ON c.user_id = u.id 
     WHERE c.post_id = ? 
     ORDER BY c.created_at DESC",
    [$post_id]
);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    $comment_content = trim($_POST['comment'] ?? '');
    
    if ($comment_content) {
        $db->query(
            "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)",
            [$post_id, $_SESSION['user_id'], $comment_content]
        );
        
        header("Location: post.php?id=$post_id");
        exit;
    }
}
?>

<div class="post-detail-container">
    <article class="post-detail">
        <div class="post-header">
            <div class="post-mood-large" style="background: <?= getMoodColor($post['mood']) ?>">
                <?= getMoodEmoji($post['mood']) ?>
            </div>
            
            <div class="post-type-badge">
                <?= $post['type'] === 'cafe' ? '☕ Café Note' : '🌫️ EchoMind' ?>
            </div>
            
            <?php if ($post['title']): ?>
                <h1 class="post-detail-title"><?= escape($post['title']) ?></h1>
            <?php endif; ?>
            
            <div class="post-author-info">
                <img src="uploads/profiles/<?= escape($post['profile_pic']) ?>" 
                     alt="Author" class="author-avatar" 
                     onerror="this.src='assets/images/default-avatar.png'">
                <div>
                    <div class="author-name"><?= escape(getAuthorDisplay($post)) ?></div>
                    <div class="post-date"><?= date('F j, Y \a\t g:i A', strtotime($post['created_at'])) ?></div>
                </div>
            </div>
        </div>
        
        <div class="post-detail-content">
            <?= nl2br(escape($post['content'])) ?>
        </div>
        
        <div class="post-detail-actions">
            <button class="reaction-btn <?= hasUserReacted($post['id'], 'heart') ? 'active' : '' ?>" 
                    data-post="<?= $post['id'] ?>" data-type="heart" onclick="toggleReaction(<?= $post['id'] ?>, 'heart')">
                ❤️ <span class="count"><?= getReactionCount($post['id'], 'heart') ?></span>
            </button>
            <button class="reaction-btn <?= hasUserReacted($post['id'], 'coffee') ? 'active' : '' ?>"
                    data-post="<?= $post['id'] ?>" data-type="coffee" onclick="toggleReaction(<?= $post['id'] ?>, 'coffee')">
                ☕ <span class="count"><?= getReactionCount($post['id'], 'coffee') ?></span>
            </button>
        </div>
    </article>
    
    <section class="comments-section">
        <h2>💬 Comments (<?= count($comments) ?>)</h2>
        
        <?php if (isLoggedIn()): ?>
            <form method="POST" class="comment-form">
                <textarea name="comment" rows="3" placeholder="Share your thoughts..." class="comment-input" required></textarea>
                <button type="submit" class="btn-submit">Post Comment</button>
            </form>
        <?php else: ?>
            <p class="login-prompt">
                <a href="login.php">Login</a> to leave a comment
            </p>
        <?php endif; ?>
        
        <div class="comments-list">
            <?php if (empty($comments)): ?>
                <p class="no-comments">No comments yet. Be the first!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <img src="uploads/profiles/<?= escape($comment['profile_pic']) ?>" 
                             alt="<?= escape($comment['username']) ?>" class="comment-avatar"
                             onerror="this.src='assets/images/default-avatar.png'">
                        <div class="comment-content">
                            <div class="comment-author">
                                <?= escape($comment['name'] ?: $comment['username']) ?>
                                <span class="comment-time"><?= timeAgo($comment['created_at']) ?></span>
                            </div>
                            <div class="comment-text"><?= nl2br(escape($comment['content'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<style>
.post-detail-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.post-detail {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2.5rem;
    margin-bottom: 2rem;
}

.post-header {
    margin-bottom: 2rem;
    position: relative;
}

.post-mood-large {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.post-type-badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    background: var(--accent-color);
    color: var(--bg-color);
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.post-detail-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
}

.post-author-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-top: 1px solid var(--border-color);
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.author-name {
    font-weight: 600;
    font-size: 1.1rem;
}

.post-date {
    opacity: 0.7;
    font-size: 0.9rem;
}

.post-detail-content {
    font-size: 1.1rem;
    line-height: 1.9;
    margin: 2rem 0;
    white-space: pre-wrap;
}

.post-detail-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.comments-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
}

.comments-section h2 {
    font-family: var(--font-display);
    margin-bottom: 1.5rem;
}

.comment-form {
    margin-bottom: 2rem;
}

.comment-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: var(--bg-color);
    color: var(--text-color);
    font-family: var(--font-body);
    margin-bottom: 1rem;
    resize: vertical;
}

.login-prompt {
    text-align: center;
    padding: 1rem;
    opacity: 0.8;
}

.login-prompt a {
    color: var(--accent-color);
    text-decoration: none;
}

.comments-list {
    margin-top: 2rem;
}

.comment {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.comment:last-child {
    border-bottom: none;
}

.comment-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.comment-content {
    flex: 1;
}

.comment-author {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.comment-time {
    font-weight: normal;
    opacity: 0.6;
    font-size: 0.85rem;
    margin-left: 0.5rem;
}

.comment-text {
    line-height: 1.6;
}

.no-comments {
    text-align: center;
    opacity: 0.6;
    padding: 2rem;
}
</style>

<?php include 'includes/footer.php'; ?>
