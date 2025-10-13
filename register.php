<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $db = Database::getInstance();
        
        // Check if username or email exists
        $existing = $db->fetchOne(
            "SELECT id FROM users WHERE username = ? OR email = ?",
            [$username, $email]
        );
        
        if ($existing) {
            $error = 'Username or email already exists';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $db->query(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
                [$username, $email, $hashed_password]
            );
            
            $success = 'Account created successfully! You can now login.';
        }
    }
}

$page_title = 'Register';
include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Join Echoes & Brews</h1>
        <p class="auth-subtitle">Create your cozy corner</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= escape($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= escape($success) ?> <a href="login.php">Login here</a></div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?= escape($_POST['username'] ?? '') ?>" placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= escape($_POST['email'] ?? '') ?>" placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="At least 6 characters">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
            </div>
            
            <button type="submit" class="btn-submit">Create Account</button>
        </form>
        
        <p class="auth-footer">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
