<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO reminders (user_id, created_at) VALUES ('$user_id', NOW())";

    if (mysqli_query($conn, $query)) {
        $_SESSION['reminderStatus'] = "<p style='color: green;'>✅ Reminder setat cu succes!</p>";
    } else {
        $_SESSION['reminderStatus'] = "<p style='color: red;'>❌ Eroare la salvarea reminderului.</p>";
    }
}

header("Location: profil.php");
exit();
?>
