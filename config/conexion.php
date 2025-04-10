<?php

// Database configuration
$host = 'localhost';
$dbname = 'eventsdb';
$user = 'myexamenik08';
$pass = 'w01XtAgb';

// Database configuration
// $host = 'localhost';
// $dbname = 'events_db';
// $user = 'root';
// $pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database Connection Error: " . $e->getMessage();
    die();
}

?>