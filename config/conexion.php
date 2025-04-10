<?php

// // Database configuration
// $host = 'exameniker.es.mialias.net';
// $dbname = 'eventsdb';
// $user = 'myexamenik08';
// $pass = 'w01XtAgb';

// // Database configuration
$host = 'localhost';
$dbname = 'events_db';
$user = 'root';
$pass = '';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo "Database Connection Error: " . $e->getMessage();
//     die();
// }

// Establish database connection using MySQLi procedural
$conn = mysqli_connect($host, $user, $pass, $dbname);


// Check connection
if (mysqli_connect_errno()) {
    echo "Database Connection Error: " . mysqli_connect_error();
    die();
}


// Set character set to UTF-8
if (!mysqli_set_charset($conn, "utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", mysqli_error($conn));
    // Consider dying here if charset is critical
}

?>