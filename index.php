ini_set('display_errors', 1);
error_reporting(E_ALL);

<?php
session_start();

// If the user is already logged in, send them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
} else {
    // If not logged in, send them to the login page
    header("Location: login.php");
    exit;
}
?>