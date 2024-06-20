<?php

require_once 'helpers/db_connectie.php';
require_once 'helpers/sanitize.php';

// Verbinding maken met de database
try {
    $db = maakVerbinding();
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Zoekfunctionaliteit hanteren
$searchTerm = ""; 
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = sanitize($_POST['search']);
    $searchQuery = "WHERE passagiernummer LIKE '%$searchTerm%' OR naam LIKE '%$searchTerm%'";
}

try {
    $query = "SELECT passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel FROM Passagier $searchQuery";
    $data = $db->query($query);
} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}

// Tabel met passagiersgegevens genereren
$passagier_table = '<table id="passagiers" class="passagierstabel">';
$passagier_table .= '<thead><tr><th>Passagiernummer</th><th>Naam</th><th>Vluchtnummer</th><th>Geslacht</th><th>Balienummer</th><th>Stoel</th><th>Bewerken</th></tr></thead>';
$passagier_table .= '<tbody>';

while ($rij = $data->fetch(PDO::FETCH_ASSOC)) {
    $passagiernummer = htmlspecialchars($rij['passagiernummer']); 
    $naam = htmlspecialchars($rij['naam']);
    $vluchtnummer = htmlspecialchars($rij['vluchtnummer']);
    $geslacht = htmlspecialchars($rij['geslacht']);
    $balienummer = htmlspecialchars($rij['balienummer']);
    $stoel = htmlspecialchars($rij['stoel']);

    // Bewerkingslink toevoegen
    $edit_link = "passagier.php?id=" . $passagiernummer;
    
    $passagier_table .= '<tr>';
    $passagier_table .= '<td>' . $passagiernummer . '</td>';
    $passagier_table .= '<td>' . $naam . '</td>';
    $passagier_table .= '<td>' . $vluchtnummer . '</td>';
    $passagier_table .= '<td>' . $geslacht . '</td>';
    $passagier_table .= '<td>' . $balienummer . '</td>';
    $passagier_table .= '<td>' . $stoel . '</td>';
    $passagier_table .= '<td><a href="' . $edit_link . '">Bewerken</a></td>';
    $passagier_table .= '</tr>';
}

$passagier_table .= '</tbody>';
$passagier_table .= '</table>';

?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Passagiers informatie</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="medewerker.php">Toevoegen</a></li>
                <li><a href="flights.php">Vluchten</a></li>
            </ul>
        </nav>
    </header>

    <section id="passengers">
        <h2>Passagiers Pagina</h2>
        <p>Welkom op de passagierspagina van Checkin Gelre.</p>

        <h2>Passagierstabel</h2>

        <form id="searchForm" method="post">
            <input type="text" id="searchInput" name="search" placeholder="Zoeken op naam of passagiernummer" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Zoeken</button>
        </form>

        <div class="table-container">
            <?php echo $passagier_table; ?>
        </div>

    </section>

    <?php
    require_once 'sub/footer.php';
    ?>
</body>
</html>
