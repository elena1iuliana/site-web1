<?php
// Evităm problemele de output
ob_start();

// Verificăm dacă sesiunea este deja activă
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    session_name("HauteFashionSession");
    session_start();
}

// Conectare la baza de date
$servername = "mysql_db";
$username = "root";
$password = "toor";
$database = "haine";

// Creăm conexiunea
$conn = new mysqli($servername, $username, $password, $database);

// Verificăm dacă există erori
if ($conn->connect_error) {
    die("❌ Eroare la conectare: " . $conn->connect_error);
}

// Setăm charset-ul pentru UTF-8
$conn->set_charset("utf8mb4");

ob_end_flush();
?>
