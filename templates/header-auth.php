<?php
// session_start() is called in the page scripts that include this header.
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CCmarket</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="../css/style.css"> <!-- Fallback for pages in subdirectories -->
</head>
<body>
    <header>
        <div class="prospect-name">CCmarket</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <?php if ($current_page !== 'login.php'): ?>
                    <li><a href="login.php">Log-in</a></li>
                <?php endif; ?>
                <?php if ($current_page !== 'register.php'): ?>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
    <div class="container"> <!-- Wrapper for content -->