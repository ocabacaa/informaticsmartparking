<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "smartparking";


$conn = new mysqli($host, $user, $pass, $db);


if ($conn->connect_errno) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Terjadi masalah pada server. Coba lagi nanti.");
}


$conn->set_charset("utf8mb4");
?>
