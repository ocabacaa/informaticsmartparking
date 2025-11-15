<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "smartparking";

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_errno) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Terjadi masalah pada server. Coba lagi nanti.");
}

// Set charset agar aman dari masalah encoding
$conn->set_charset("utf8mb4");
?>
