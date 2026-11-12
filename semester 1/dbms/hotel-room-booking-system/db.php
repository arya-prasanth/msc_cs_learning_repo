<?php
// db.php - shared database connection (included by booking.php and report.php)
$host   = "localhost";
$user   = "root";   // XAMPP default user
$pass   = "";       // XAMPP default password (empty)
$dbname = "hotel_db";

mysqli_report(MYSQLI_REPORT_OFF); // errors are handled manually so we can show friendly messages

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . htmlspecialchars($conn->connect_error));
}
$conn->set_charset("utf8mb4");
