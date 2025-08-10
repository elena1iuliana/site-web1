<?php
session_start();
include("config.php");

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION["user_id"])) {
    header("Location: haine.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$paymentMessage = "";

// Procesăm plata (ștergem produsele din coș)
if (isset($_GET['action']) && $_GET['action'] === 'pay') {
    $deleteQuery = "DELETE FROM cos WHERE id_utilizator = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $paymentMessage = "✅ Plata a fost efectuată cu succes!";
    } else {
        $paymentMessage = "❌ Eroare la procesarea plății: " . mysqli_error($conn);
    }
    $stmt->close();
}

// Obținem produsele din coș
$query = "SELECT cos.id_haine, cos.cantitate, haine.nume, haine.imagine, haine.pret 
          FROM cos
          JOIN haine ON cos.id_haine = haine.id
          WHERE cos.id_utilizator = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Coșul Meu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Meniu de navigare -->
    <nav class="navbar">
        <a href="haine.php">Haine</a>
        <a href="cart.php">Coș</a>
        <a href="profil.php">Profil</a>
        <a href="index.php">Home</a>
    </nav>

    <h1>Coșul Meu</h1>

    <!-- Afișăm mesajul de plată dacă este cazul -->
    <?php if (!empty($paymentMessage)): ?>
        <h2><?= htmlspecialchars($paymentMessage, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>

    <section class="cos-container">
        <?php if ($result->num_rows > 0): ?>
            <?php $total = 0; ?>
            <?php while ($produs = $result->fetch_assoc()): ?>
                <div class="produs_in_cos">
                    <img src="<?= htmlspecialchars($produs['imagine']); ?>" alt="<?= htmlspecialchars($produs['nume']); ?>">
                    <h2><?= htmlspecialchars($produs['nume']); ?></h2>
                    <p>Cantitate: <?= htmlspecialchars($produs['cantitate']); ?></p>
                    <p>Preț: <strong><?= htmlspecialchars($produs['pret']); ?> RON</strong></p>

                    <!-- Form pentru ștergerea produsului -->
                    <form action="sterge_din_cos.php" method="post">
                        <input type="hidden" name="id_haine" value="<?= $produs['id_haine']; ?>">
                        <button type="submit">❌ Șterge</button>
                    </form>
                </div>
                <?php $total += $produs['pret'] * $produs['cantitate']; ?>
            <?php endwhile; ?>

            <h2>Total: <?= number_format($total, 2); ?> RON</h2>
            <button onclick="window.location.href='cart.php?action=pay'">💳 Plătește</button>

        <?php else: ?>
            <p>Coșul este gol!</p>
        <?php endif; ?>
    </section>

</body>
</html>
