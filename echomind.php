<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
requireLogin();

$page_title = 'EchoMind Journal';
require_once 'includes/header.php';

$db = Database::getInstance();

// Get user's posts (private and public)
$posts = $db->fetchAll(
    "SELECT * FROM posts WHERE user_id = ? AND type = 'echo' ORDER BY created_at DESC",
    [$_SESSION['user_id']]
);

// Get mood tracker data for current month
$current_month = date('Y-m');
$moods = $db->fetchAll(
    "SELECT * FROM moods WHERE user_id = ? AND date LIKE ? ORDER BY date",
    [$_SESSION['user_id'], $current_month . '%']
);

$show_form = isset($_GET['new']) || isset($_POST['submit']);
?>

<div class="echomind-container">
    <div class="echomind-header">
        <h1>🌫️ EchoMind</h1>
        <p>Your private sanctuary for thoughts and reflections</p>
    </div>
    
    <div class="echomind-layout">
        <aside class="mood-tracker-widget">
            <h3>Mood Tracker</h3>
            <div class="mood-calendar">
                <?php
                $days_in_month = date('t');
                for ($day = 1; $day <= $days_in_month; $day++) {
                    $date = $current_month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $mood = null;
                    foreach ($moods as $m) {
                        if ($m['date'] === $date) {
                            $mood = $m['mood_type'];
                            break;
                        }
                    }
                    
                    $color = $mood ? getMoodColor($mood) : 'transparent';
                    $emoji = $mood ? getMoodEmoji($mood) : $day;
                    echo "<div class='mood-day' style='background: $color' title='$date'>$emoji</div>";
                }
                ?>
            </div>
            
            <div class="mood-legend">
                <div><span style="background: <?= getMoodColor('happy') ?>"></span> Happy</div>
                <div><span style="background: <?= getMoodColor('melancholy') ?>"></span> Melancholy</div>
                <div><span style="background: <?= getMoodColor('calm') ?>"></span> Calm</div>
                <div><span style="background: <?= getMoodColor('reflective') ?>"></span> Reflective</div>
            </div>
        </aside>
        
        <main class="journal-main">
            <?php if ($show_form): ?>
                <div class="journal-editor">
                    <h2>New Echo Entry</h2>
                    <form action="api/create_post.php" method="POST" class="post-form">
                        <input type="hidden" name="type" value="echo">
                        
                        <div class="form-group">
                            <input type="text" name="title" placeholder="Entry title (optional)" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <textarea name="content" rows="10" placeholder="What's echoing in your mind today?" 
                                      class="form-control" required></textarea>
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
                                <label>Visibility</label>
                                <select name="visibility" class="form-control">
                                    <option value="private">🔒 Private (only you)</option>
                                    <option value="public">🌍 Public</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>If public, post as</label>
                            <select name="anonymity" class="form-control">
                                <option value="anonymous">Anonymous</option>
                                <option value="username">@<?= escape($current_user['username']) ?></option>
                                <option value="fullname"><?= escape($current_user['name'] ?: $current_user['username']) ?></option>
                            </select>
                        </div>
                        
                        <button type="submit" name="submit" class="btn-submit">Save Echo 🌫️</button>
                    </form>
                </div>
            <?php endif; ?>
            
            <div class="journal-entries">
                <div class="entries-header">
                    <h2>Your Echo Timeline</h2>
                    <?php if (!$show_form): ?>
                        <a href="echomind.php?new=1" class="btn-primary">+ New Entry</a>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($posts)): ?>
                    <div class="empty-state">
                        <p>Your journal awaits... Write your first echo.</p>
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($posts as $post): ?>
                            <article class="timeline-entry">
                                <div class="entry-date">
                                    <div class="date-circle" style="background: <?= getMoodColor($post['mood']) ?>">
                                        <?= getMoodEmoji($post['mood']) ?>
                                    </div>
                                    <span class="date-text"><?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                                </div>
                                
                                <div class="entry-content">
                                    <?php if ($post['title']): ?>
                                        <h3 class="entry-title"><?= escape($post['title']) ?></h3>
                                    <?php endif; ?>
                                    
                                    <div class="entry-text">
                                        <?= nl2br(escape($post['content'])) ?>
                                    </div>
                                    
                                    <div class="entry-meta">
                                        <span class="visibility">
                                            <?= $post['visibility'] === 'private' ? '🔒 Private' : '🌍 Public' ?>
                                        </span>
                                        <span class="time"><?= timeAgo($post['created_at']) ?></span>
                                        <a href="post.php?id=<?= $post['id'] ?>" class="entry-link">View →</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<style>
.echomind-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.echomind-header {
    text-align: center;
    margin-bottom: 3rem;
}

.echomind-header h1 {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.echomind-header p {
    font-family: var(--font-handwritten);
    font-size: 1.2rem;
    opacity: 0.8;
}

.echomind-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
}

.mood-tracker-widget {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    height: fit-content;
    position: sticky;
    top: 100px;
}

.mood-tracker-widget h3 {
    font-family: var(--font-display);
    margin-bottom: 1rem;
}

.mood-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.mood-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    font-size: 0.9rem;
    cursor: pointer;
}

.mood-legend {
    font-size: 0.85rem;
}

.mood-legend div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.3rem;
}

.mood-legend span {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    display: inline-block;
}

.journal-editor {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-bottom: 2rem;
}

.journal-editor h2 {
    font-family: var(--font-display);
    margin-bottom: 1.5rem;
}

.entries-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.entries-header h2 {
    font-family: var(--font-display);
}

.timeline {
    position: relative;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 40px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-entry {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.entry-date {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    min-width: 120px;
}

.date-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.date-text {
    font-size: 0.9rem;
    opacity: 0.7;
    margin-top: 1rem;
}

.entry-content {
    flex: 1;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.entry-title {
    font-family: var(--font-display);
    margin-bottom: 1rem;
}

.entry-text {
    line-height: 1.8;
    margin-bottom: 1rem;
}

.entry-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
    font-size: 0.9rem;
    opacity: 0.7;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.entry-link {
    margin-left: auto;
    color: var(--accent-color);
    text-decoration: none;
}

@media (max-width: 1024px) {
    .echomind-layout {
        grid-template-columns: 1fr;
    }
    
    .mood-tracker-widget {
        position: static;
    }
}

@media (max-width: 768px) {
    .timeline::before {
        left: 25px;
    }
    
    .entry-date {
        flex-direction: column;
        align-items: center;
        min-width: 80px;
    }
    
    .date-circle {
        width: 40px;
        height: 40px;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
