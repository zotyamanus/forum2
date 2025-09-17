<?php
// JSON fájl elérési útja
$jsonFile = 'topics.json';

// JSON fájl betöltése
function loadTopics() {
    global $jsonFile;
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        return json_decode($jsonData, true);
    }
    return [];
}

// Téma hozzáadása
if (isset($_POST['add'])) {
    $theme_name = $_POST['theme_name'];
    if (!empty($theme_name)) {
        $topics = loadTopics();
        $topics[] = ['name' => $theme_name];  // Új téma hozzáadása
        file_put_contents($jsonFile, json_encode($topics, JSON_PRETTY_PRINT)); // Mentés
        echo "Új téma hozzáadva!";
    }
}

// Téma törlése
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $topics = loadTopics();
    if (isset($topics[$deleteId])) {
        unset($topics[$deleteId]);  // Téma törlése
        $topics = array_values($topics);  // Az indexek újraszámozása
        file_put_contents($jsonFile, json_encode($topics, JSON_PRETTY_PRINT)); // Mentés
        echo "Téma törölve!";
    }
}

// Témák betöltése
$topics = loadTopics();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Téma kezelése</title>
</head>
<body>
    <h1>Téma kiválasztása és kezelése</h1>

    <!-- Téma hozzáadása form -->
    <form action="index.php" method="POST">
        <input type="text" name="theme_name" placeholder="Új téma neve" required>
        <button type="submit" name="add">Téma hozzáadása</button>
    </form>

    <h2>Témák listája</h2>
    <ul>
        <?php
        if (count($topics) > 0) {
            foreach ($topics as $index => $topic) {
                echo "<li>" . htmlspecialchars($topic['name']) . " 
                      <a href='?delete=" . $index . "'>Törlés</a></li>";
            }
        } else {
            echo "<li>Nincsenek témák.</li>";
        }
        ?>
    </ul>
</body>
</html>
