<?php
session_start();
include("config.php");

// Activăm afișarea erorilor pentru debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nume'], $_POST['prenume'], $_POST['email'], $_POST['parola'])) {
    $nume = mysqli_real_escape_string($conn, $_POST['nume']);
    $prenume = mysqli_real_escape_string($conn, $_POST['prenume']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $parola = password_hash($_POST['parola'], PASSWORD_DEFAULT);
    $role = "user"; // Setăm automat rolul implicit ca "user"

    // Verificăm dacă emailul există deja în baza de date
    $check_query = "SELECT id FROM utilizatori WHERE email=?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<p style='color: red;'>❌ Acest email există deja!</p>";
        exit();
    }
    $stmt->close();

    // Inserăm utilizatorul în baza de date
    $query = "INSERT INTO utilizatori (nume, prenume, email, parola, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nume, $prenume, $email, $parola, $role);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Te-ai înregistrat cu succes!</p>";
        $_SESSION['register_success'] = "✅ Te-ai înregistrat cu succes!";
        
        exit();
    } else {
        echo "<p style='color: red;'>❌ Eroare la înregistrare: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Înregistrează-te</h1>

    <?php if (isset($_SESSION['register_success'])): ?>
        <p class="success-message"><?= $_SESSION['register_success']; unset($_SESSION['register_success']); ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
        <label for="nume">Nume:</label>
        <input type="text" id="nume" name="nume" required>

        <label for="prenume">Prenume:</label>
        <input type="text" id="prenume" name="prenume" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="parola">Parolă:</label>
        <input type="password" id="parola" name="parola" required>

        <button type="submit">Înregistrează-te</button>
    </form>
</body>
</html>
