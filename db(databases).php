<?php
// db.php - զանգի սա ամեն էջի վերևում
$DB_HOST = 'localhost';
$DB_NAME = 'jewelry_db';
$DB_USER = 'dbuser';
$DB_PASS = 'dbpass';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (PDOException $e) {
    // Զգուշացում՝ իրական արտադրական կայքում մի ցուցադրեք ամբողջ սխալի տեքստը
    exit('Database connection failed: ' . $e->getMessage());
}
