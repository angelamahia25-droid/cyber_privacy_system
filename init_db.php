<?php
try {
    $db = new PDO('sqlite:db/privacy_system.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Users Table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE,
        password TEXT
    )");

    // 2. Data Assets Table
    $db->exec("CREATE TABLE IF NOT EXISTS data_assets (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        asset_name TEXT,
        data_type TEXT,
        sensitivity TEXT,
        sample_data TEXT, -- <--- NEW COLUMN
        is_encrypted INTEGER DEFAULT 0
    )");


    // 3. Risk Logs Table (For Scan Results)
    $db->exec("CREATE TABLE IF NOT EXISTS risk_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        asset_id INTEGER,
        risk_level TEXT,
        found_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (asset_id) REFERENCES data_assets(id)
    )");

    // Create default admin: admin / admin123
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $db->exec("INSERT OR IGNORE INTO users (username, password) VALUES ('admin', '$hash')");

    echo "Database Schema Initialized with 3 Tables.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}