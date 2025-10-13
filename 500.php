<?php
http_response_code(500);
$page_title = '500 - Server Error';
require_once 'includes/header.php';
?>

<div class="error-page">
    <div class="error-content">
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Server Error</h2>
        <p class="error-message">
            Our coffee machine seems to be brewing errors instead of comfort.
        </p>
        <div class="error-emoji">☕💥</div>
        <p class="error-help">Please try again in a moment. If the problem persists, contact support.</p>
        <a href="index.php" class="btn-primary">Return Home</a>
    </div>
</div>

<style>
.error-page {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.error-content {
    text-align: center;
    max-width: 600px;
}

.error-code {
    font-family: var(--font-display);
    font-size: 8rem;
    background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.error-title {
    font-family: var(--font-display);
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.error-message {
    font-family: var(--font-handwritten);
    font-size: 1.3rem;
    opacity: 0.8;
    margin-bottom: 1rem;
}

.error-help {
    opacity: 0.7;
    margin-bottom: 2rem;
}

.error-emoji {
    font-size: 4rem;
    margin: 2rem 0;
}

.btn-primary {
    display: inline-block;
    padding: 1rem 2rem;
    background: var(--accent-color);
    color: var(--bg-color);
    text-decoration: none;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
</style>

<?php include 'includes/footer.php'; ?>
