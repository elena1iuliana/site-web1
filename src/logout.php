<?php
session_start();
include("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    if (isset($_COOKIE['user_id'])) {
        setcookie("user_id", "", time() - 3600, "/");
    }

    $_SESSION = [];
    session_destroy();

    header("Location: index.php");
    exit();
}
?>
