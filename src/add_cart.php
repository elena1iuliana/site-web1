<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("config.php");

// Setăm user_id la 9 pentru test, dacă nu e deja setat
if (!isset($_SESSION["user_id"])) {
    $_SESSION["user_id"] = 9;
}
$user_id = $_SESSION["user_id"];

// Preluăm datele din formular
$id_produs = isset($_POST['id_produs']) ? mysqli_real_escape_string($conn, $_POST['id_produs']) : '';
$cantitate = isset($_POST['cantitate']) ? mysqli_real_escape_string($conn, $_POST['cantitate']) : '';

if(empty($id_produs) || empty($cantitate)){
    // Dacă lipsește vreun dată, redirecționează înapoi la haine.php
    header("Location: haine.php");
    exit;
}

$query = "INSERT INTO cos (id_utilizator, id_haine, cantitate) VALUES ('$user_id', '$id_produs', '$cantitate')";
if (mysqli_query($conn, $query)) {
    // După o inserare reușită, redirecționează către pagina cos (cart.php)
    header("Location: cart.php");
    exit;
} else {
    // Dacă apare o eroare la inserare, afișează eroarea (opțional poți redirecționa tot la haine.php cu un mesaj de eroare)
    echo "Eroare la inserare: " . mysqli_error($conn);
}
?>
