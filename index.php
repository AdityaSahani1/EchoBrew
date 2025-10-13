<?php
$page_title = 'Home';
require_once 'includes/header.php';

$db = Database::getInstance();

// Get featured posts
$featured_posts = $db->fetchAll(
    "SELECT p.*, u.username, u.name 
     FROM posts p 
     JOIN users u ON p.user_id = u.id 
     WHERE p.visibility = 'public' 
     ORDER BY p.created_at DESC 
     LIMIT 6"
);

$site_quote = getSetting('site_quote', 'Pour your thoughts. Sip your peace.');
?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">
            <span class="hero-main">Echoes & Brews</span>
            <span class="hero-sub">🌙</span>
        </h1>
        <p class="hero-quote"><?= escape($site_quote) ?></p>
        
        <div class="hero-actions">
            <a href="cafe.php?new=1" class="btn-hero cafe">
                <span class="btn-icon">☕</span>
                Write a Cafe Note
            </a>
            <a href="echomind.php?new=1" class="btn-hero echo">
                <span class="btn-icon">🌫️</span>
                Open Your Journal
            </a>
        </div>
    </div>
</div>

<section class="mood-selector">
    <h2>How are you feeling today?</h2>
    <div class="mood-buttons">
        <button class="mood-btn" data-mood="happy">
            <span class="mood-emoji">🌤️</span>
            <span class="mood-label">Happy</span>
        </button>
        <button class="mood-btn" data-mood="melancholy">
            <span class="mood-emoji">🌧️</span>
            <span class="mood-label">Melancholy</span>
        </button>
        <button class="mood-btn" data-mood="calm">
            <span class="mood-emoji">🌙</span>
            <span class="mood-label">Calm</span>
        </button>
        <button class="mood-btn" data-mood="reflective">
            <span class="mood-emoji">💭</span>
            <span class="mood-label">Reflective</span>
        </button>
    </div>
</section>

<section class="featured-section">
    <h2 class="section-title">Featured Writings</h2>
    
    <?php if (empty($featured_posts)): ?>
        <div class="empty-state">
            <p>No posts yet. Be the first to share your thoughts!</p>
        </div>
    <?php else: ?>
        <div class="posts-grid">
            <?php foreach ($featured_posts as $post): ?>
                <article class="post-card">
                    <div class="post-mood" style="background-color: <?= getMoodColor($post['mood']) ?>">
                        <?= getMoodEmoji($post['mood']) ?>
                    </div>
                    
                    <?php if ($post['title']): ?>
                        <h3 class="post-title"><?= escape($post['title']) ?></h3>
                    <?php endif; ?>
                    
                    <div class="post-preview">
                        <?= escape(substr(strip_tags($post['content']), 0, 150)) ?>...
                    </div>
                    
                    <div class="post-meta">
                        <span class="post-author"><?= escape(getAuthorDisplay($post)) ?></span>
                        <span class="post-time"><?= timeAgo($post['created_at']) ?></span>
                    </div>
                    
                    <div class="post-reactions">
                        <span class="reaction">
                            ❤️ <?= getReactionCount($post['id'], 'heart') ?>
                        </span>
                        <span class="reaction">
                            ☕ <?= getReactionCount($post['id'], 'coffee') ?>
                        </span>
                    </div>
                    
                    <a href="post.php?id=<?= $post['id'] ?>" class="post-link">Read more</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="quote-section">
    <blockquote class="daily-quote">
        <p><?= escape(getSetting('daily_quote', 'Where your thoughts find a quiet table, and your feelings find a cup of warmth.')) ?></p>
        <cite>— Echoes & Brews</cite>
    </blockquote>
</section>

<?php include 'includes/footer.php'; ?>
