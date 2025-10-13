<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$page_title = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';

$db = Database::getInstance();

// Get statistics
$stats = [
    'users' => $db->fetchOne("SELECT COUNT(*) as count FROM users")['count'],
    'posts' => $db->fetchOne("SELECT COUNT(*) as count FROM posts")['count'],
    'comments' => $db->fetchOne("SELECT COUNT(*) as count FROM comments")['count'],
    'reactions' => $db->fetchOne("SELECT COUNT(*) as count FROM reactions")['count']
];

// Get recent users
$recent_users = $db->fetchAll(
    "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 5"
);

// Get recent posts
$recent_posts = $db->fetchAll(
    "SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC LIMIT 5"
);

// Mood statistics
$mood_stats = $db->fetchAll(
    "SELECT mood, COUNT(*) as count FROM posts GROUP BY mood ORDER BY count DESC"
);
?>

<div class="admin-container">
    <div class="admin-header">
        <h1>⚙️ Admin Dashboard</h1>
        <p>Manage your Echoes & Brews café</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <div class="stat-value"><?= $stats['users'] ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-info">
                <div class="stat-value"><?= $stats['posts'] ?></div>
                <div class="stat-label">Total Posts</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">💬</div>
            <div class="stat-info">
                <div class="stat-value"><?= $stats['comments'] ?></div>
                <div class="stat-label">Comments</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">❤️</div>
            <div class="stat-info">
                <div class="stat-value"><?= $stats['reactions'] ?></div>
                <div class="stat-label">Reactions</div>
            </div>
        </div>
    </div>
    
    <div class="admin-grid">
        <section class="admin-section">
            <h2>Recent Users</h2>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_users as $user): ?>
                            <tr>
                                <td><?= escape($user['username']) ?></td>
                                <td><?= escape($user['email']) ?></td>
                                <td><span class="badge badge-<?= $user['role'] ?>"><?= escape($user['role']) ?></span></td>
                                <td><?= timeAgo($user['created_at']) ?></td>
                                <td>
                                    <a href="user_detail.php?id=<?= $user['id'] ?>" class="action-btn">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="users.php" class="view-all">View All Users →</a>
        </section>
        
        <section class="admin-section">
            <h2>Recent Posts</h2>
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Mood</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_posts as $post): ?>
                            <tr>
                                <td><?= escape(substr($post['title'] ?: $post['content'], 0, 30)) ?>...</td>
                                <td><?= escape($post['username']) ?></td>
                                <td><?= $post['type'] === 'cafe' ? '☕' : '🌫️' ?></td>
                                <td><?= getMoodEmoji($post['mood']) ?></td>
                                <td>
                                    <a href="../post.php?id=<?= $post['id'] ?>" class="action-btn" target="_blank">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="posts.php" class="view-all">View All Posts →</a>
        </section>
    </div>
    
    <div class="admin-grid">
        <section class="admin-section">
            <h2>Mood Distribution</h2>
            <div class="mood-chart">
                <?php foreach ($mood_stats as $mood): ?>
                    <div class="mood-bar">
                        <div class="mood-label">
                            <?= getMoodEmoji($mood['mood']) ?> <?= ucfirst($mood['mood']) ?>
                        </div>
                        <div class="mood-progress">
                            <div class="mood-fill" style="width: <?= ($mood['count'] / $stats['posts'] * 100) ?>%; background: <?= getMoodColor($mood['mood']) ?>"></div>
                        </div>
                        <div class="mood-count"><?= $mood['count'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        
        <section class="admin-section">
            <h2>Quick Actions</h2>
            <div class="quick-actions">
                <a href="settings.php" class="quick-action-btn">
                    <span class="action-icon">⚙️</span>
                    <span class="action-text">Site Settings</span>
                </a>
                <a href="users.php" class="quick-action-btn">
                    <span class="action-icon">👥</span>
                    <span class="action-text">Manage Users</span>
                </a>
                <a href="posts.php" class="quick-action-btn">
                    <span class="action-icon">📝</span>
                    <span class="action-text">Manage Posts</span>
                </a>
                <a href="../index.php" class="quick-action-btn">
                    <span class="action-icon">🏠</span>
                    <span class="action-text">View Site</span>
                </a>
            </div>
        </section>
    </div>
</div>

<style>
.admin-container {
    max-width: 1400px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.admin-header {
    text-align: center;
    margin-bottom: 3rem;
}

.admin-header h1 {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.admin-header p {
    font-family: var(--font-handwritten);
    font-size: 1.2rem;
    opacity: 0.8;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 3rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-color);
}

.stat-label {
    opacity: 0.7;
    font-size: 0.9rem;
}

.admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.admin-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 1.5rem;
}

.admin-section h2 {
    font-family: var(--font-display);
    margin-bottom: 1.5rem;
}

.data-table {
    overflow-x: auto;
    margin-bottom: 1rem;
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 0.75rem;
    border-bottom: 2px solid var(--border-color);
    font-weight: 600;
}

.data-table td {
    padding: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-admin {
    background: var(--accent-color);
    color: var(--bg-color);
}

.badge-user {
    background: var(--border-color);
    color: var(--text-color);
}

.action-btn {
    padding: 0.25rem 0.75rem;
    background: var(--accent-color);
    color: var(--bg-color);
    text-decoration: none;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    transition: var(--transition);
}

.action-btn:hover {
    opacity: 0.9;
}

.view-all {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    margin-top: 0.5rem;
}

.view-all:hover {
    text-decoration: underline;
}

.mood-chart {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.mood-bar {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.mood-label {
    min-width: 120px;
    font-size: 0.9rem;
}

.mood-progress {
    flex: 1;
    height: 30px;
    background: var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.mood-fill {
    height: 100%;
    transition: var(--transition);
}

.mood-count {
    min-width: 40px;
    text-align: right;
    font-weight: 600;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem;
    background: var(--bg-color);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--text-color);
    transition: var(--transition);
}

.quick-action-btn:hover {
    background: var(--accent-color);
    color: var(--bg-color);
    border-color: var(--accent-color);
}

.action-icon {
    font-size: 2rem;
}

.action-text {
    font-weight: 500;
}

@media (max-width: 768px) {
    .admin-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
