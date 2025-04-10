<?php
// public_html/scripts/db_check.php
//require_once __DIR__ . '/env_parser.php';

//$env = parseEnv();
//$relativePath = $env['DB_PATH'] ?? '../database/app.db'; // fallback

//$absolutePath = realpath(__DIR__ . '/../../' . dirname($relativePath)) . '/' . basename($relativePath);
//$databasePath = $absolutePath ?: (__DIR__ . '../database/app.db');


//$dir = dirname($databasePath);
//if (!file_exists($dir)) {
    //mkdir($dir, 0755, true);
//}

try {
    $db = new PDO('sqlite:../database/app.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT CHECK(role IN ('Admin', 'User')) NOT NULL
    )");
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


?>

