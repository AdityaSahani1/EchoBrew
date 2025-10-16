<?php
$page_title = 'Explore';
require_once 'includes/header.php';

$db = Database::getInstance();

// Get trending tags (mock - you can implement actual tag system later)
$search = $_GET['search'] ?? '';
$mood = $_GET['mood'] ?? '';
$type_filter = $_GET['type'] ?? '';

$sql = "SELECT p.*, u.username, u.name 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.visibility = 'public'";

$params = [];

if ($search) {
    $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($mood && in_array($mood, ['happy', 'melancholy', 'calm', 'reflective', 'other'])) {
    $sql .= " AND p.mood = ?";
    $params[] = $mood;
}

if ($type_filter && in_array($type_filter, ['cafe', 'echo'])) {
    $sql .= " AND p.type = ?";
    $params[] = $type_filter;
}

$sql .= " ORDER BY RAND() LIMIT 20";
$posts = $db->fetchAll($sql, $params);

// Get random post for "Daily Brew"
$daily_brew = $db->fetchOne(
    "SELECT p.*, u.username, u.name 
     FROM posts p 
     JOIN users u ON p.user_id = u.id 
     WHERE p.visibility = 'public' 
     ORDER BY RAND() 
     LIMIT 1"
);
?>

<div class="explore-container">
    <div class="explore-header">
        <h1>🔍 Explore</h1>
        <p>Discover new writings, moods, and authors</p>
    </div>
    
    <div class="search-section">
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search posts..." 
                   value="<?= escape($search) ?>" class="search-input">
            
            <select name="mood" class="search-select">
                <option value="">All Moods</option>
                <option value="happy" <?= $mood === 'happy' ? 'selected' : '' ?>>🌤️ Happy</option>
                <option value="melancholy" <?= $mood === 'melancholy' ? 'selected' : '' ?>>🌧️ Melancholy</option>
                <option value="calm" <?= $mood === 'calm' ? 'selected' : '' ?>>🌙 Calm</option>
                <option value="reflective" <?= $mood === 'reflective' ? 'selected' : '' ?>>💭 Reflective</option>
            </select>
            
            <select name="type" class="search-select">
                <option value="">All Types</option>
                <option value="cafe" <?= $type_filter === 'cafe' ? 'selected' : '' ?>>☕ Café Notes</option>
                <option value="echo" <?= $type_filter === 'echo' ? 'selected' : '' ?>>🌫️ EchoMind</option>
            </select>
            
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
    
    <?php if ($daily_brew): ?>
        <div class="daily-brew">
            <h2>✨ Daily Brew</h2>
            <div class="brew-card">
                <div class="brew-mood" style="background: <?= getMoodColor($daily_brew['mood']) ?>">
                    <?= getMoodEmoji($daily_brew['mood']) ?>
                </div>
                
                <?php if ($daily_brew['title']): ?>
                    <h3><?= escape($daily_brew['title']) ?></h3>
                <?php endif; ?>
                
                <p><?= escape(substr($daily_brew['content'], 0, 200)) ?>...</p>
                
                <div class="brew-meta">
                    <span><?= escape(getAuthorDisplay($daily_brew)) ?></span>
                    <a href="post.php?id=<?= $daily_brew['id'] ?>" class="read-link">Read More →</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="explore-results">
        <h2>Discover</h2>
        
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>No posts found. Try different search criteria.</p>
            </div>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <div class="post-mood" style="background-color: <?= getMoodColor($post['mood']) ?>">
                            <?= getMoodEmoji($post['mood']) ?>
                        </div>
                        
                        <span class="post-type"><?= $post['type'] === 'cafe' ? '☕' : '🌫️' ?></span>
                        
                        <?php if ($post['title']): ?>
                            <h3 class="post-title"><?= escape($post['title']) ?></h3>
                        <?php endif; ?>
                        
                        <div class="post-preview">
                            <?= escape(substr(strip_tags($post['content']), 0, 120)) ?>...
                        </div>
                        
                        <div class="post-meta">
                            <span class="post-author"><?= escape(getAuthorDisplay($post)) ?></span>
                            <span class="post-time"><?= timeAgo($post['created_at']) ?></span>
                        </div>
                        
                        <a href="post.php?id=<?= $post['id'] ?>" class="post-link">Read more</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.explore-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.explore-header {
    text-align: center;
    margin-bottom: 3rem;
}

.explore-header h1 {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.explore-header p {
    font-family: var(--font-handwritten);
    font-size: 1.2rem;
    opacity: 0.8;
}

.search-section {
    margin-bottom: 3rem;
}

.search-form {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.search-input {
    flex: 1;
    min-width: 200px;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: var(--card-bg);
    color: var(--text-color);
}

.search-select {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background: var(--card-bg);
    color: var(--text-color);
}

.search-btn {
    padding: 0.75rem 2rem;
    background: var(--accent-color);
    color: var(--bg-color);
    border: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.daily-brew {
    margin-bottom: 3rem;
}

.daily-brew h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.brew-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
    position: relative;
}

.brew-mood {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.brew-card h3 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    padding-right: 60px;
}

.brew-card p {
    line-height: 1.7;
    margin-bottom: 1rem;
}

.brew-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.read-link {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 500;
}

.explore-results h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.post-type {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 1.2rem;
}
</style>

<?php include 'includes/footer.php'; ?>
