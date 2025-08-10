<?php
session_start();
include("config.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("❌ Acces interzis!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_produs'], $_POST['nume'], $_POST['pret'])) {
    $id_produs = intval($_POST['id_produs']);
    $nume = mysqli_real_escape_string($conn, $_POST['nume']);
    $pret = floatval($_POST['pret']);
    
    $setImage = "";
    $target_file = null;

    // Verificăm dacă utilizatorul a încărcat o imagine nouă
    if (!empty($_FILES["imagine"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . "." . strtolower(pathinfo($_FILES["imagine"]["name"], PATHINFO_EXTENSION));

        // Mutăm imaginea în locația corectă
        if (move_uploaded_file($_FILES["imagine"]["tmp_name"], $target_file)) {
            $setImage = ", imagine=?";
        } else {
            die("❌ Eroare la încărcarea imaginii!");
        }
    }

    // Construim interogarea SQL corectă
    if ($setImage) {
        $query = "UPDATE haine SET nume=?, pret=? $setImage WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdsi", $nume, $pret, $target_file, $id_produs);
    } else {
        $query = "UPDATE haine SET nume=?, pret=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sdi", $nume, $pret, $id_produs);
    }

    // Executăm interogarea și verificăm succesul operației
    if ($stmt->execute()) {
        header("Location: admin_panel.php?success=updated");
        exit();
    } else {
        echo "❌ Eroare la actualizare: " . $stmt->error;
    }

    // Închidem statement-ul
    $stmt->close();
}
?>
