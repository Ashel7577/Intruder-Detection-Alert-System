<?php
$db_host = 'localhost';
$db_user = 'tracker';
$db_pass = 'tracker_password_123';
$db_name = 'clicktracker';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

