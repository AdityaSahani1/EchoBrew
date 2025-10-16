<?php
$page_title = 'About';
require_once 'includes/header.php';
?>

<div class="about-container">
    <div class="about-hero">
        <h1>About Echoes & Brews</h1>
        <p class="tagline">"Where your thoughts find a quiet table, and your feelings find a cup of warmth."</p>
    </div>
    
    <div class="about-content">
        <section class="story-section">
            <h2>Our Story</h2>
            <p>
                Echoes & Brews was born from a simple idea: everyone needs a cozy corner to pour their thoughts,
                a safe space where emotions can breathe and creativity can flow freely.
            </p>
            <p>
                We created this digital café as a sanctuary for writers, dreamers, and thinkers—a place where
                you can share your joy, process your melancholy, celebrate your calm, or explore your reflections.
            </p>
        </section>
        
        <section class="philosophy-section">
            <h2>Our Philosophy</h2>
            <div class="philosophy-grid">
                <div class="philosophy-card">
                    <span class="philosophy-icon">☕</span>
                    <h3>Warmth & Comfort</h3>
                    <p>Every interaction is designed to feel like settling into your favorite café chair with a warm drink.</p>
                </div>
                
                <div class="philosophy-card">
                    <span class="philosophy-icon">🌙</span>
                    <h3>Privacy & Freedom</h3>
                    <p>Choose to be yourself, anonymous, or anything in between. Your comfort, your rules.</p>
                </div>
                
                <div class="philosophy-card">
                    <span class="philosophy-icon">💭</span>
                    <h3>Authentic Expression</h3>
                    <p>No algorithms, no pressure—just genuine human connection through words and feelings.</p>
                </div>
                
                <div class="philosophy-card">
                    <span class="philosophy-icon">✨</span>
                    <h3>Creative Community</h3>
                    <p>A space where every thought matters and every voice adds to our collective harmony.</p>
                </div>
            </div>
        </section>
        
        <section class="features-section">
            <h2>What We Offer</h2>
            <ul class="features-list">
                <li><strong>Café Notes:</strong> Share public posts, poems, and thoughts with the community</li>
                <li><strong>EchoMind Journal:</strong> Your private sanctuary for personal reflections</li>
                <li><strong>Mood Tracking:</strong> Visualize your emotional journey over time</li>
                <li><strong>Anonymity Options:</strong> Post as yourself, your username, or anonymously</li>
                <li><strong>Cozy Design:</strong> Lo-fi aesthetic with dark/light modes for any mood</li>
            </ul>
        </section>
        
        <section class="join-section">
            <h2>Join the Café</h2>
            <p>
                Whether you're here to share your stories, find inspiration, or simply enjoy the peaceful ambiance,
                we're glad you found us. Grab a cup, find a comfortable spot, and make yourself at home.
            </p>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn-join">Create Your Account</a>
            <?php endif; ?>
        </section>
    </div>
</div>

<style>
.about-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.about-hero {
    text-align: center;
    padding: 3rem 0;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 3rem;
}

.about-hero h1 {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 1rem;
}

.tagline {
    font-family: var(--font-handwritten);
    font-size: 1.5rem;
    opacity: 0.9;
}

.about-content section {
    margin-bottom: 3rem;
}

.about-content h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.about-content p {
    line-height: 1.8;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.philosophy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
}

.philosophy-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
    transition: var(--transition);
}

.philosophy-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.philosophy-icon {
    font-size: 3rem;
    display: block;
    margin-bottom: 1rem;
}

.philosophy-card h3 {
    font-family: var(--font-display);
    margin-bottom: 0.5rem;
}

.philosophy-card p {
    font-size: 0.95rem;
    opacity: 0.8;
}

.features-list {
    list-style: none;
    padding: 0;
}

.features-list li {
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-color);
    line-height: 1.6;
}

.features-list li:last-child {
    border-bottom: none;
}

.features-list strong {
    color: var(--accent-color);
    font-weight: 600;
}

.join-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
    text-align: center;
}

.btn-join {
    display: inline-block;
    margin-top: 1.5rem;
    padding: 1rem 2rem;
    background: var(--accent-color);
    color: var(--bg-color);
    text-decoration: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
}

.btn-join:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style>

<?php include 'includes/footer.php'; ?>
