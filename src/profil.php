<?php
session_start();
include("config.php");

// Restaurăm sesiunea din cookie dacă utilizatorul a ales "Remember Me"
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}

$user_id = $_SESSION['user_id'] ?? null;
$user = null;
$reminderStatus = $_SESSION['reminderStatus'] ?? null;
$reminder = null;

if ($user_id) {
    $query = "SELECT * FROM utilizatori WHERE id='$user_id'";
    $res = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($res);

    $query = "SELECT created_at FROM reminders WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1";
    $result = mysqli_query($conn, $query);
    $reminder = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Profil Utilizator</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="navbar">
        <a href="haine.php">Haine</a>
        <a href="cart.php">Coș</a>
        <a href="profil.php">Profil</a>
         <a href="index.php">Home</a>
    </nav>

    <div class="container">
        <?php if ($user): ?>
            <h1>Profilul lui <?php echo htmlspecialchars($user['nume'] . " " . $user['prenume']); ?></h1>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
           
            <?php if ($reminderStatus): ?>
                <p><?php echo $reminderStatus; ?></p>
            <?php endif; ?>

            <?php if ($reminder): ?>
                <p>Ultimul reminder: <?php echo htmlspecialchars($reminder['created_at']); ?></p>
            <?php endif; ?>

            <form method="post" action="logout.php">
                <button type="submit" name="logout">Delogare</button>
            </form>
        <?php else: ?>
            <h2>Autentificare</h2>
            <form method="post" action="login.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="parola" placeholder="Parola" required>
                <button type="submit" name="login">Autentifică-te</button>
            </form>
            
            <h2>Înregistrare</h2>
            <form method="post" action="register.php">
                <input type="text" name="nume" placeholder="Nume" required>
                <input type="text" name="prenume" placeholder="Prenume" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="parola" placeholder="Parola" required>
                <button type="submit">Înregistrează-te</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
