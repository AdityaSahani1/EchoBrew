-- SQLite Schema for Echoes & Brews
-- For local testing

CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    name TEXT,
    profile_pic TEXT DEFAULT 'default.png',
    bio TEXT,
    role TEXT DEFAULT 'user' CHECK(role IN ('user', 'admin')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_email ON users(email);

CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title TEXT,
    content TEXT NOT NULL,
    mood TEXT DEFAULT 'calm' CHECK(mood IN ('happy', 'melancholy', 'calm', 'reflective', 'other')),
    type TEXT NOT NULL CHECK(type IN ('cafe', 'echo')),
    visibility TEXT DEFAULT 'public' CHECK(visibility IN ('public', 'private')),
    anonymity TEXT DEFAULT 'username' CHECK(anonymity IN ('anonymous', 'username', 'fullname')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_type ON posts(type);
CREATE INDEX IF NOT EXISTS idx_visibility ON posts(visibility);
CREATE INDEX IF NOT EXISTS idx_mood ON posts(mood);
CREATE INDEX IF NOT EXISTS idx_created ON posts(created_at);

CREATE TABLE IF NOT EXISTS comments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_post ON comments(post_id);

CREATE TABLE IF NOT EXISTS reactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('heart', 'coffee', 'comment')),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(post_id, user_id, type),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_post_reaction ON reactions(post_id);

CREATE TABLE IF NOT EXISTS moods (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    date DATE NOT NULL,
    mood_type TEXT NOT NULL CHECK(mood_type IN ('happy', 'melancholy', 'calm', 'reflective', 'other')),
    notes TEXT,
    UNIQUE(user_id, date),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_user_date ON moods(user_id, date);

CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT OR IGNORE INTO settings (setting_key, setting_value) VALUES
('site_quote', 'Pour your thoughts. Sip your peace.'),
('daily_quote', 'Where your thoughts find a quiet table, and your feelings find a cup of warmth.'),
('maintenance_mode', '0');

-- Create default admin user (password: admin123)
INSERT OR IGNORE INTO users (username, password, email, name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@echoesbrews.com', 'Administrator', 'admin');
