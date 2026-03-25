<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If the user_id session is not set, they are not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page immediately
    header("Location: login.php");
    exit;
}
?>