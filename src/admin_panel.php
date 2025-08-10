<?php
session_start();
include("config.php");

// Verificăm dacă utilizatorul este admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Acces interzis! Această pagină este doar pentru administratori.");
}

// Procesăm căutarea produselor
$searchTerm = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search"])) {
    $searchTerm = trim(mysqli_real_escape_string($conn, $_POST["search"] ?? ""));
    $query = "SELECT * FROM haine WHERE nume LIKE '%$searchTerm%'";
} else {
    $query = "SELECT * FROM haine";
}

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Eroare la interogare: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Panou Admin - Haine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Meniu de navigare -->
    <nav class="navbar">
        <a href="haine.php">Haine</a>
        <a href="cart.php">Coș</a>
        <a href="profil.php">Profil</a>
         <a href="index.php">Home</a>
        <a href="admin_panel.php" class="admin-button">Panou Admin</a>
    </nav>

    <h1>Administrare Produse</h1>

    <!-- Formular de căutare -->
    <form method="post">
        <input type="text" name="search" placeholder="Caută haine..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Caută</button>
    </form>

    <!-- Buton pentru adăugare produse -->
    <a href="adauga_produs.php" class="admin-button">➕ Adaugă Produs</a>

    <div class="produse-container">
    <?php while ($produs = mysqli_fetch_assoc($result)): ?>
        <div class="produs">
            <img src="<?php echo htmlspecialchars($produs['imagine'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?>">
            <h2><?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>Preț: <?php echo htmlspecialchars($produs['pret'], ENT_QUOTES, 'UTF-8'); ?> RON</p>

            <!-- Opțiuni pentru admin -->
            <form method="POST" action="modifica_produs.php">
                <input type="hidden" name="id_produs" value="<?php echo $produs['id']; ?>">
                <button type="submit">✏️ Modifică</button>
            </form>

            <form method="POST" action="sterge_produs.php">
                <input type="hidden" name="id_produs" value="<?php echo $produs['id']; ?>">
                <button type="submit" style="background-color: red;">🗑️ Șterge</button>
            </form>
        </div>
    <?php endwhile; ?>
    </div>
</body>
</html>

