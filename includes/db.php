<?php
try {
    // Connects to the SQLite file in the 'db' folder
    $pdo = new PDO('sqlite:' . __DIR__ . '/../db/privacy_system.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>