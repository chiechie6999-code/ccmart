<?php
session_start();
require_once 'templates/header-auth.php';

// Ensure user is authorized to reset password
if (!isset($_SESSION['password_reset_authorized']) || !$_SESSION['password_reset_authorized']) {
    header('Location: forgot_password.php');
    exit();
}

$error = $_SESSION['error'] ?? '';
$message = $_SESSION['message'] ?? '';
unset($_SESSION['error'], $_SESSION['message']);
?>

<div class="container">
    <h2>Change Password</h2>
    <p>Please enter your new password.</p>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($message): ?>
        <div class="success-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="php/change_password_process.php" method="post" id="change-password-form">
        <div class="form-group">
            <label for="password">Enter Password <span class="required">*</span></label>
            <input type="password" name="password" id="password" required>
            <div id="password-strength"></div>
        </div>
        <div class="form-group">
            <label for="re_password">Re-enter Password <span class="required">*</span></label>
            <input type="password" name="re_password" id="re_password" required>
        </div>
        <button type="submit">Change Password</button>
    </form>
</div>

<?php require_once 'templates/footer.php'; ?>