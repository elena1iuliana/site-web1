<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Acces interzis!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_produs'])) {
    $id_produs = intval($_POST['id_produs']);

    $query = "SELECT * FROM haine WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_produs);
    $stmt->execute();
    $result = $stmt->get_result();
    $produs = $result->fetch_assoc();

    if (!$produs) {
        die("❌ Produs inexistent.");
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Modifică Produs</title>
</head>
<body>
    <h1>Modifică produsul</h1>
    <form action="update_produs.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id_produs" value="<?= $produs['id']; ?>">
        <input type="text" name="nume" value="<?= htmlspecialchars($produs['nume']); ?>" required>
        <input type="number" name="pret" value="<?= htmlspecialchars($produs['pret']); ?>" required>
        <input type="file" name="imagine" accept="image/*">
        <button type="submit">Actualizează</button>
    </form>
</body>
</html>
