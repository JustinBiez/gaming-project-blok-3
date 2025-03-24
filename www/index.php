<?php
require 'database.php';

$platform = $_GET['platform'] ?? '';
$genre = $_GET['genre'] ?? '';
$sort = $_GET['sort'] ?? '';
$font_size = $_GET['font_size'] ?? 'normal';

$sql = 'SELECT * FROM games WHERE 1=1';
if ($platform) $sql .= " AND platform = '" . $platform . "'";
if ($genre) $sql .= " AND genre = '" . $genre . "'";
if ($sort === 'price_asc') $sql .= ' ORDER BY prijs ASC';
if ($sort === 'price_desc') $sql .= ' ORDER BY prijs DESC';

$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

$platforms = mysqli_query($conn, 'SELECT DISTINCT platform FROM games ORDER BY platform');
$genres = mysqli_query($conn, 'SELECT DISTINCT genre FROM games ORDER BY genre');
?>

<!DOCTYPE html>
<html>
<head>
    <title>GAMES</title>
    <link rel="stylesheet" href="style.css?v="<?php echo time(); ?>>
</head>

<body class="font-<?php echo htmlspecialchars($font_size); ?>">
    <div class="container">
        <h1>GAMES LIBRARY</h1>
        
        <div class="controls">
            <form method="get" class="filters" id="filterForm">
                <select name="platform" onchange="this.form.submit()">
                    <option value="">All Platforms</option>
                    <?php while ($p = mysqli_fetch_assoc($platforms)): ?>
                        <option value="<?php echo htmlspecialchars($p['platform']); ?>"
                                <?php echo $platform === $p['platform'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['platform']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="genre" onchange="this.form.submit()">
                    <option value="">All Genres</option>
                    <?php while ($g = mysqli_fetch_assoc($genres)): ?>
                        <option value="<?php echo htmlspecialchars($g['genre']); ?>"
                                <?php echo $genre === $g['genre'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($g['genre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="sort" onchange="this.form.submit()">
                    <option value="">Sorteer bij...</option>
                    <option value="price_asc" <?php echo $sort === 'price_asc' ? 'selected' : ''; ?>>Prijs (Laag naar Hoog)</option>
                    <option value="price_desc" <?php echo $sort === 'price_desc' ? 'selected' : ''; ?>>Prijs (Hoog naar Laag)</option>
                </select>

                <select name="font_size" onchange="this.form.submit()">
                    <option value="small" <?php echo $font_size === 'small' ? 'selected' : ''; ?>>Small Text</option>
                    <option value="normal" <?php echo $font_size === 'normal' ? 'selected' : ''; ?>>Normal Text</option>
                    <option value="large" <?php echo $font_size === 'large' ? 'selected' : ''; ?>>Large Text</option>
                </select>
            </form>
            
            <p class="result-count"><?php echo $count; ?> games worden getoond</p>
        </div>

        <div class="games-grid">
            <?php while ($game = mysqli_fetch_assoc($result)): ?>
                <div class="game-card">
                    <a href="game.php?id=<?php echo $game['id']; ?>">
                        <img src="<?php echo htmlspecialchars("img/" . $game['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($game['titel']); ?>">
                        <h2><?php echo htmlspecialchars($game['titel']); ?></h2>
                        <p class="publisher"><?php echo htmlspecialchars($game['uitgever']); ?></p>
                        <p class="release-date"><?php echo date('d-m-Y', strtotime($game['releasedate'])); ?></p>
                        <p class="price">€<?php echo number_format($game['prijs'], 2); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        document.querySelectorAll('.game-card').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                
                card.classList.add('mouse-tracking');
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.classList.remove('mouse-tracking');
                card.style.transform = '';
            });
        });
    </script>
</body>
</html>