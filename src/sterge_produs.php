<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Acces interzis!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_produs'])) {
    $id_produs = intval($_POST['id_produs']);

    // Ștergem produsul din cos înainte de a-l șterge din haine
    $query = "DELETE FROM cos WHERE id_haine=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_produs);
    $stmt->execute();
    $stmt->close();

    // Ștergem produsul din haine
    $query = "DELETE FROM haine WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_produs);
    if ($stmt->execute()) {
        header("Location: admin_panel.php?success=deleted");
    } else {
        echo "❌ Eroare la ștergere.";
    }

    $stmt->close();
    exit();
}
?>