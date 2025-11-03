<?php
session_start();
require_once 'templates/header-auth.php';

// Retrieve data from session
$errors = $_SESSION['errors'] ?? [];
$input = $_SESSION['input'] ?? [];
$success_message = $_SESSION['success_message'] ?? '';
$login_attempts = $_SESSION['login_attempts'] ?? 0;
$lockout_time = $_SESSION['lockout_time'] ?? 0;

unset($_SESSION['errors'], $_SESSION['input'], $_SESSION['success_message']); // Clear session data after use
?>

<div class="container"
     data-login-attempts="<?php echo $login_attempts; ?>"
     data-lockout-time="<?php echo $lockout_time; ?>"
     data-current-time="<?php echo time(); ?>">

    <h2>Log-in</h2>

    <?php if ($success_message): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <div id="login-error-message" class="error-message">
        <?php echo htmlspecialchars($errors['login'] ?? ''); ?>
    </div>

    <form action="php/login_process.php" method="post" id="login-form">
        <div class="form-group">
            <label for="username">Username <span class="required">*</span></label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($input['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password <span class="required">*</span></label>
            <div class="password-field">
                <input type="password" name="password" id="password" required>
                <button type="button" class="toggle-password">Show</button>
            </div>
        </div>

        <div class="form-group" id="forgot-password-container" style="display: none;">
            <a href="forgot_password.php">Forgot Password? Reset Here</a>
        </div>

        <button type="submit" id="login-button">Log-in</button>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php" id="register-link">Please register here</a></p>
        </div>
    </form>
</div>

<?php require_once 'templates/footer.php'; ?>