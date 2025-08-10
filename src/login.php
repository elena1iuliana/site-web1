<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $parola = trim($_POST['parola'] ?? '');
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($parola)) {
        die("❌ Email sau parolă goale. Introdu datele corect.");
    }

    // Selectăm utilizatorul și rolul lui
    $query = "SELECT id, role, parola FROM utilizatori WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($parola, $user['parola'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // Salvăm rolul utilizatorului în sesiune

        // Dacă utilizatorul a selectat "Remember Me", salvăm și cookie-ul
        if ($remember) {
            setcookie("user_id", $user['id'], time() + (86400 * 30), "/", "", false, true);
            setcookie("role", $user['role'], time() + (86400 * 30), "/", "", false, true);
        }

        // Redirecționăm utilizatorul în funcție de rol
        if ($user['role'] === 'admin') {
            header("Location: admin_panel.php"); // **Admin merge la panoul admin**
        } else {
            header("Location: profil.php"); // **User merge la profil**
        }
        exit();
    } else {
        echo "❌ Email sau parolă incorecte.";
    }

    $stmt->close();
}
?>
