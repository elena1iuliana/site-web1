<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Acces interzis!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nume'], $_POST['pret'], $_FILES['imagine'])) {
    $nume = mysqli_real_escape_string($conn, $_POST['nume']);
    $pret = floatval($_POST['pret']);

    // Încărcarea imaginii
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target_file = $target_dir . uniqid() . "." . strtolower(pathinfo($_FILES["imagine"]["name"], PATHINFO_EXTENSION));
    move_uploaded_file($_FILES["imagine"]["tmp_name"], $target_file);

    // Inserarea în baza de date
    $query = "INSERT INTO haine (nume, pret, imagine) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sds", $nume, $pret, $target_file);

    if ($stmt->execute()) {
        header("Location: admin_panel.php?success=added");
    } else {
        echo "❌ Eroare la adăugarea produsului: " . $stmt->error;
    }

    $stmt->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Produs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Adaugă un nou produs</h1>

    <form action="adauga_produs.php" method="post" enctype="multipart/form-data">
        <label for="nume">Nume produs:</label>
        <input type="text" id="nume" name="nume" required>

        <label for="pret">Preț:</label>
        <input type="number" id="pret" name="pret" required>

        <label for="imagine">Imagine:</label>
        <input type="file" id="imagine" name="imagine" accept="image/*" required>

        <button type="submit">Adaugă produs</button>
    </form>
</body>
</html>

