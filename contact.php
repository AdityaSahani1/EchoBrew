<?php
$page_title = 'Contact';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        // Here you would normally send an email using PHPMailer
        // For now, we'll just show a success message
        $success = 'Thank you for your message! We\'ll get back to you soon.';
        
        // Clear form
        $_POST = [];
    }
}
?>

<div class="contact-container">
    <div class="contact-header">
        <h1>📧 Contact Us</h1>
        <p>We'd love to hear from you</p>
    </div>
    
    <div class="contact-content">
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p>Have questions, suggestions, or just want to say hello? Drop us a message!</p>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <span class="method-icon">✉️</span>
                    <div>
                        <h3>Email</h3>
                        <p>hello@echoesbrews.com</p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <span class="method-icon">📱</span>
                    <div>
                        <h3>Social Media</h3>
                        <p>Follow us @echoesbrews</p>
                    </div>
                </div>
                
                <div class="contact-method">
                    <span class="method-icon">💡</span>
                    <div>
                        <h3>Suggestions</h3>
                        <p>We're always improving!</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="contact-form-section">
            <?php if ($success): ?>
                <div class="alert alert-success"><?= escape($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= escape($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" class="contact-form">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required 
                           value="<?= escape($_POST['name'] ?? '') ?>" placeholder="Your name">
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= escape($_POST['email'] ?? '') ?>" placeholder="your@email.com">
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" 
                           value="<?= escape($_POST['subject'] ?? '') ?>" placeholder="What's this about?">
                </div>
                
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required 
                              placeholder="Tell us what's on your mind..."><?= escape($_POST['message'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn-submit">Send Message ☕</button>
            </form>
        </div>
    </div>
</div>

<style>
.contact-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

.contact-header {
    text-align: center;
    margin-bottom: 3rem;
}

.contact-header h1 {
    font-family: var(--font-display);
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.contact-header p {
    font-family: var(--font-handwritten);
    font-size: 1.2rem;
    opacity: 0.8;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.contact-info h2 {
    font-family: var(--font-display);
    font-size: 2rem;
    margin-bottom: 1rem;
}

.contact-info > p {
    margin-bottom: 2rem;
    line-height: 1.6;
    opacity: 0.9;
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-method {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.method-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.contact-method h3 {
    font-family: var(--font-display);
    margin-bottom: 0.25rem;
}

.contact-method p {
    opacity: 0.8;
    font-size: 0.95rem;
}

.contact-form-section {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    padding: 2rem;
}

@media (max-width: 768px) {
    .contact-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include 'includes/footer.php'; ?>
