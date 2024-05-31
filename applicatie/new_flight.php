<?php

require_once 'db_connectie.php';
require_once 'sanitize.php';

try {
    $db = maakVerbinding();
} catch (PDOException $e) {
    die("Error connecting to database: " . $e->getMessage());
}

// Handle search functionality
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = sanitize($_POST['search']);
    $searchQuery = "WHERE bestemming LIKE '%$searchTerm%' OR vluchtnummer LIKE '%$searchTerm%'";
}

try {
    $query = "SELECT vluchtnummer, bestemming, gatecode, vertrektijd FROM Vlucht $searchQuery";
    $data = $db->query($query);
} catch (PDOException $e) {
    die("Error executing query: " . $e->getMessage());
}


$vlucht_table = '<table id="vluchten" class="vluchtentabel">';
$vlucht_table .= '<thead><tr><th>Vluchtnummer</th><th>Bestemming</th><th>Gate</th><th>Vertrektijd</th></tr></thead>';
$vlucht_table .= '<tbody>';

while ($rij = $data->fetch(PDO::FETCH_ASSOC)) {
    $vluchtnummer = htmlspecialchars($rij['vluchtnummer']); 
    $bestemming = htmlspecialchars($rij['bestemming']);
    $gatecode = htmlspecialchars($rij['gatecode']);
    $vertrektijd = htmlspecialchars($rij['vertrektijd']);

    $vlucht_table .= '<tr><td>' . $vluchtnummer . '</td><td>' . $bestemming . '</td><td>' . $gatecode . '</td><td>' . $vertrektijd . '</td></tr>';
}

$vlucht_table .= '</tbody>';
$vlucht_table .= '</table>';

?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Vluchten</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="new_flight.php">Nieuwe Vlucht</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section id="flights">
        <h2>Vluchten Pagina</h2>
        <p>Welkom op de vluchtenpagina van Checkin Gelre.</p>

        <h2>Gegevenstabel</h2>

        <form id="searchForm" method="post">
            <input type="text" id="searchInput" name="search" placeholder="Zoeken op naam">
            <button type="submit">Zoeken</button>
        </form>

        <?php echo $vlucht_table; ?>
    </section>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>


</html>