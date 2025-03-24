<?php
require 'database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "SELECT * FROM games WHERE id = " . mysqli_real_escape_string($conn, $id);
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: index.php');
    exit;
}

$game = mysqli_fetch_assoc($result);
$font_size = $_GET['font_size'] ?? 'normal';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($game['titel']); ?> - Game Details</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="font-<?php echo htmlspecialchars($font_size); ?>">
    <div class="container">
        <div class="back-button">
            <a href="index.php">← Back to Games</a>
        </div>

        <div class="game-details">
            <div class="game-header">
                <img src="<?php echo htmlspecialchars("img/" . $game['thumbnail_url']); ?>" 
                     alt="<?php echo htmlspecialchars($game['titel']); ?>">
                <div class="game-info">
                    <h1><?php echo htmlspecialchars($game['titel']); ?></h1>
                    <p class="publisher">Uitgever: <?php echo htmlspecialchars($game['uitgever']); ?></p>
                    <p class="platform">Platform: <?php echo htmlspecialchars($game['platform']); ?></p>
                    <p class="genre">Genre: <?php echo htmlspecialchars($game['genre']); ?></p>
                    <p class="release-date">Release Date: <?php echo date('F j, Y', strtotime($game['releasedate'])); ?></p>
                    <p class="price">Prijs: €<?php echo number_format($game['prijs'], 2); ?></p>
                </div>
            </div>

            <div class="game-description">
                <h2>Beschrijving</h2>
                <p><?php echo nl2br(htmlspecialchars($game['beschrijving'])); ?></p>
            </div>
        </div>
    </div>
</body>
</html>