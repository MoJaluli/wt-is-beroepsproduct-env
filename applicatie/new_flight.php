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
<html lang="nl">
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

    <main>
        <section id="searchFlight">
            <h2>Zoek Vluchten</h2>
            <form action="new_flight.php" method="post">
                <input type="text" name="search" placeholder="Zoek op bestemming of vluchtnummer" required>
                <button type="submit">Zoeken</button>
            </form>
        </section>

        <?php
        require_once 'db_connectie.php';
        require_once 'sanitize.php';

        try {
            $db = maakVerbinding();
        } catch (PDOException $e) {
            die("Error connecting to database: " . $e->getMessage());
        }

        // Execute the query to retrieve flight data
        try {
            $query = "SELECT vluchtnummer, bestemming, gatecode, vertrektijd FROM Vlucht $searchQuery";
            $data = $db->query($query);
        } catch (PDOException $e) {
            die("Error executing query: " . $e->getMessage());
        }

        // Create the flight table
        $vlucht_table = '<table id="vluchten" class="vluchtentabel">';
        $vlucht_table .= '<thead><tr><th>Vluchtnummer</th><th>Bestemming</th><th>Gate</th><th>Vertrektijd</th></tr></thead>';
        $vlucht_table .= '<tbody>';

        // Fetch each row of the result set and add it to the table
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
        <section id="flightTable">
            <h2>Vluchten</h2>
            <?php echo $vlucht_table; ?>
        </section>
        <section id="newFlight">
            <h2>Nieuwe Vlucht</h2>
            <p>Voeg een nieuwe vlucht toe aan de database.</p>
            <form action="new_flight_verwerk.php" method="post">
                <label for="vluchtnummer">Vluchtnummer:</label>
                <input type="text" id="vluchtnummer" name="vluchtnummer" required>
                <label for="bestemming">Bestemming:</label>
                <input type="text" id="bestemming" name="bestemming" required>
                <label for="gatecode">Gatecode:</label>
                <input type="text" id="gatecode" name="gatecode" required>
                <label for="vertrektijd">Vertrektijd:</label>
                <input type="datetime-local" id="vertrektijd" name="vertrektijd" required>
                <button type="submit">Voeg toe</button>
            </form>
        </section>
        </main>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
