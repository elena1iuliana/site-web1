<?php
include("config.php");

if (!isset($_SESSION["user_id"])) {
    $_SESSION["user_id"] = 9;
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
    <title>Haine - Magazin Second-Hand</title>
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

    <h1>Toate Hainele</h1>

    <!-- Formular de căutare -->
    <form method="post">
        <input type="text" name="search" placeholder="Caută haine..." value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit">Caută</button>
    </form>

    <div class="produse-container">
    <?php
    while ($produs = mysqli_fetch_assoc($result)) {
    ?>
        <div class="produs">
            <img src="<?php echo htmlspecialchars($produs['imagine'], ENT_QUOTES, 'UTF-8'); ?>" 
                 alt="<?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?>">
            <h2><?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p>Preț: <?php echo htmlspecialchars($produs['pret'], ENT_QUOTES, 'UTF-8'); ?> RON</p>
            <!-- Formular de adăugare în coș -->
            <form method="POST" action="add_cart.php">
                <input type="hidden" name="id_produs" value="<?php echo $produs['id']; ?>">
                <input type="hidden" name="cantitate" value="1">
                <button type="submit">Adaugă</button>
            </form>
        </div>
    <?php
    }
    ?>
    </div>
</body>
</html>

