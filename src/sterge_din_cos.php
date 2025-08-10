<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_haine'])) {
    $id_haine = intval($_POST['id_haine']);

    // Ștergem produsul doar al utilizatorului conectat
    $query = "DELETE FROM cos WHERE id_haine = ? AND id_utilizator = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_haine, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: cart.php?success=deleted");
    } else {
        echo "❌ Eroare la ștergerea produsului: " . $stmt->error;
    }

    $stmt->close();
    exit();
}
?>
