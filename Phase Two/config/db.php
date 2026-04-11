<?php
/**
 * Database Configuration
 * Uses PDO for secure and flexible database operations.
 */
$host = 'localhost';
$db   = 'bbteam';
$user = 'root';
$pass = ''; // Default XAMPP/WAMP password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // In a production environment, you should log the error and show a generic message.
     die("Connection failed: " . $e->getMessage());
}
?>
