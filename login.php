<?php
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'All fields are required';
    } else {
        $db = Database::getInstance();
        
        $user = $db->fetchOne(
            "SELECT id, username, password, role FROM users WHERE username = ? OR email = ?",
            [$username, $username]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }
}

$page_title = 'Login';
include 'includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Pour yourself in</p>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= escape($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required 
                       value="<?= escape($_POST['username'] ?? '') ?>" placeholder="Enter your username or email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            
            <button type="submit" class="btn-submit">Login</button>
        </form>
        
        <p class="auth-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
