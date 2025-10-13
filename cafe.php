<?php
$page_title = 'Café Notes';
require_once 'includes/header.php';

$db = Database::getInstance();

// Filter by mood if specified
$mood_filter = $_GET['mood'] ?? '';
$sql = "SELECT p.*, u.username, u.name 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.type = 'cafe' AND p.visibility = 'public'";

if ($mood_filter && in_array($mood_filter, ['happy', 'melancholy', 'calm', 'reflective', 'other'])) {
    $sql .= " AND p.mood = ?";
    $params = [$mood_filter];
} else {
    $params = [];
}

$sql .= " ORDER BY p.created_at DESC";
$posts = $db->fetchAll($sql, $params);

// Show new post form if ?new=1
$show_form = isset($_GET['new']) || isset($_POST['submit']);
?>

<div class="page-header">
    <h1 class="page-title">☕ Café Notes</h1>
    <p class="page-subtitle">Share your thoughts with the world</p>
</div>

<?php if ($show_form && isLoggedIn()): ?>
    <div class="new-post-section">
        <div class="post-editor">
            <h2>Write a Café Note</h2>
            <form action="api/create_post.php" method="POST" class="post-form">
                <input type="hidden" name="type" value="cafe">
                
                <div class="form-group">
                    <input type="text" name="title" placeholder="Give your note a title (optional)" class="form-control">
                </div>
                
                <div class="form-group">
                    <textarea name="content" rows="8" placeholder="Pour your thoughts here..." class="form-control" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Mood</label>
                        <select name="mood" class="form-control">
                            <option value="calm">🌙 Calm</option>
                            <option value="happy">🌤️ Happy</option>
                            <option value="melancholy">🌧️ Melancholy</option>
                            <option value="reflective">💭 Reflective</option>
                            <option value="other">✨ Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Post as</label>
                        <select name="anonymity" class="form-control">
                            <option value="username">@<?= escape($current_user['username']) ?></option>
                            <option value="fullname"><?= escape($current_user['name'] ?: $current_user['username']) ?></option>
                            <option value="anonymous">Anonymous</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn-submit">Publish Note ☕</button>
            </form>
        </div>
    </div>
<?php elseif ($show_form): ?>
    <div class="alert alert-info">
        Please <a href="login.php">login</a> to write a café note.
    </div>
<?php endif; ?>

<div class="filter-bar">
    <a href="cafe.php" class="filter-btn <?= !$mood_filter ? 'active' : '' ?>">All</a>
    <a href="cafe.php?mood=happy" class="filter-btn <?= $mood_filter === 'happy' ? 'active' : '' ?>">🌤️ Happy</a>
    <a href="cafe.php?mood=melancholy" class="filter-btn <?= $mood_filter === 'melancholy' ? 'active' : '' ?>">🌧️ Melancholy</a>
    <a href="cafe.php?mood=calm" class="filter-btn <?= $mood_filter === 'calm' ? 'active' : '' ?>">🌙 Calm</a>
    <a href="cafe.php?mood=reflective" class="filter-btn <?= $mood_filter === 'reflective' ? 'active' : '' ?>">💭 Reflective</a>
</div>

<div class="posts-container">
    <?php if (empty($posts)): ?>
        <div class="empty-state">
            <p>No café notes yet. Be the first to share!</p>
            <?php if (isLoggedIn()): ?>
                <a href="cafe.php?new=1" class="btn-primary">Write a Note</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="posts-masonry">
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <div class="post-mood" style="background-color: <?= getMoodColor($post['mood']) ?>">
                        <?= getMoodEmoji($post['mood']) ?>
                    </div>
                    
                    <?php if ($post['title']): ?>
                        <h3 class="post-title"><?= escape($post['title']) ?></h3>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <?= nl2br(escape(substr($post['content'], 0, 200))) ?>
                        <?php if (strlen($post['content']) > 200): ?>...<?php endif; ?>
                    </div>
                    
                    <div class="post-meta">
                        <span class="post-author"><?= escape(getAuthorDisplay($post)) ?></span>
                        <span class="post-time"><?= timeAgo($post['created_at']) ?></span>
                    </div>
                    
                    <div class="post-actions">
                        <button class="reaction-btn <?= hasUserReacted($post['id'], 'heart') ? 'active' : '' ?>" 
                                data-post="<?= $post['id'] ?>" data-type="heart" onclick="toggleReaction(<?= $post['id'] ?>, 'heart')">
                            ❤️ <span class="count"><?= getReactionCount($post['id'], 'heart') ?></span>
                        </button>
                        <button class="reaction-btn <?= hasUserReacted($post['id'], 'coffee') ? 'active' : '' ?>"
                                data-post="<?= $post['id'] ?>" data-type="coffee" onclick="toggleReaction(<?= $post['id'] ?>, 'coffee')">
                            ☕ <span class="count"><?= getReactionCount($post['id'], 'coffee') ?></span>
                        </button>
                        <a href="post.php?id=<?= $post['id'] ?>" class="read-more">Read more →</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (isLoggedIn() && !$show_form): ?>
    <a href="cafe.php?new=1" class="floating-btn" title="Write a new note">
        ✍️
    </a>
<?php endif; ?>

<style>
.page-header {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
    text-align: center;
}

.page-title {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-family: var(--font-handwritten);
    font-size: 1.3rem;
    opacity: 0.8;
}

.new-post-section {
    max-width: 800px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.post-editor {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
}

.post-editor h2 {
    font-family: var(--font-display);
    margin-bottom: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.filter-bar {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--text-color);
    transition: var(--transition);
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--accent-color);
    color: var(--bg-color);
    border-color: var(--accent-color);
}

.posts-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.posts-masonry {
    column-count: 3;
    column-gap: 1.5rem;
}

.posts-masonry .post-card {
    break-inside: avoid;
    margin-bottom: 1.5rem;
}

.post-content {
    margin: 1rem 0;
    line-height: 1.7;
}

.post-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.reaction-btn {
    background: none;
    border: 1px solid var(--border-color);
    padding: 0.4rem 0.8rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.9rem;
}

.reaction-btn:hover,
.reaction-btn.active {
    background: var(--accent-color);
    color: var(--bg-color);
    border-color: var(--accent-color);
}

.read-more {
    margin-left: auto;
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 500;
}

.floating-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    background: var(--accent-color);
    color: var(--bg-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    text-decoration: none;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
}

.floating-btn:hover {
    transform: scale(1.1);
}

@media (max-width: 1024px) {
    .posts-masonry {
        column-count: 2;
    }
}

@media (max-width: 768px) {
    .posts-masonry {
        column-count: 1;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
