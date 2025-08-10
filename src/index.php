<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<head>

    <meta charset="UTF-8">
    <title>Second-Hand Fashion</title>
    <link rel="stylesheet" href="style.css">

</head>
    <!-- Meniu de navigare -->
    <nav class="navbar">
        <a href="haine.php"><img src="poze/home.png" alt="Haine"> Haine</a>
        <a href="cart.php"><img src="poze/credit-car.png" alt="Coș"> Coș</a>
        <a href="profil.php"><img src="poze/user.png" alt="Profil"> Profil</a>
        <a href="index.php"><img src="poze/homee.png" alt="Home">Home</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin_panel.php"><img src="poze/setting.png" alt="Setări Admin"> Setări Admin</a>
        <?php endif; ?>
    </nav>


    <!-- Secțiunea principală -->
    <main class="container">
        <h1>Bine ai venit la Second-Hand Fashion!</h1>
        <p>Explorează colecțiile noastre unice și găsește stilul perfect pentru tine.</p>

        <section class="haine-container">
            <?php
            $query = "SELECT * FROM haine LIMIT 3";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Eroare la interogare: " . mysqli_error($conn));
            }

            while ($produs = mysqli_fetch_assoc($result)) {
                ?>
                <div class="haine">
                    <img src="<?php echo htmlspecialchars($produs['imagine'], ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="<?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?>">
                    <h2><?php echo htmlspecialchars($produs['nume'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p>Preț: <strong><?php echo htmlspecialchars($produs['pret'], ENT_QUOTES, 'UTF-8'); ?> RON</strong></p>
                    <a href="haine.php">Vezi mai multe</a>
                </div>
                <?php
            }
            ?>
        </section>
    </main>

  <!-- Google Maps -->
    <div class="map-container">
        <div id="map"></div>
    </div>
    <script>
        function initMap() {
            var uaic = { lat: 47.174, lng: 27.573 };
            var map = new google.maps.Map(document.getElementById('map'), { 
                zoom: 15, 
                center: uaic 
            });
            new google.maps.Marker({ position: uaic, map: map });
        }
    </script>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2712.1571899625856!2d27.568929076616108!3d47.17435887115352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40cafb61af5ef507%3A0x95f1e37c73c23e74!2sUniversitatea%20%E2%80%9EAlexandru%20Ioan%20Cuza%E2%80%9D!5e0!3m2!1sro!2sro!4v1748891084970!5m2!1sro!2sro" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    
    <!-- Videoclip centrat -->
    <div class="video-container">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/nmnjL26OBcY?si=d2S30LQqtiRKzrA5" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
    </div>

    <!-- Footer cu butoane Social Media -->
    <footer>
        <div class="social-buttons">
        <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.uaic.ro" target="_blank">
    <img src="poze/share.png" alt="Share pe Facebook" width="32" height="32">
</a>
  <a href="https://www.facebook.com/UAIC.Iasi" target="_blank">
        <img src="poze/like.png" alt="Like UAIC" width="32" height="32">
    </a>
        <p>&copy; 2025 Second-Hand Fashion | Toate drepturile rezervate.</p>
    </footer>

</body>
</html>
