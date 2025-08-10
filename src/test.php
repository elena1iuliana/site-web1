<?php
$to = "test@example.com"; // Înlocuiește cu o adresă validă
$subject = "Test Email";
$message = "Acesta este un test de trimitere email prin PHP.";
$headers = "From: admin@siteul-tau.com\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "<p style='color: green;'>✅ Email trimis cu succes!</p>";
} else {
    echo "<p style='color: red;'>❌ Eroare la trimiterea emailului.</p>";
}
?>