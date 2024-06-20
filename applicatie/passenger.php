<?php
session_start();
require_once 'helpers/db_connectie.php';

// Haal de gebruikersnaam op uit de sessie
$gebruikersnaam = $_SESSION['passagier'];

// Maak verbinding met de database
$db = maakVerbinding();

// Bereid de SQL-query voor
$query = "SELECT passagiernummer, naam, vluchtnummer, geslacht, balienummer, stoel FROM Passagier WHERE passagiernummer = :passagiernummer";

// Voer de query uit
$stmt = $db->prepare($query);
$stmt->execute([':passagiernummer' => $gebruikersnaam]);

// Haal de gebruiker op
$gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <style>
    </style>
</head>

<body>
    <header>
        <h1>Checkin Gelre</h1>
        <nav>
            <ul>
                <li><a href="Home.php">Startpagina</a></li>
                <li><a href="sub/vluchten2.php">vluchten</a></li>
            </ul>
        </nav>
    </header>

    <h1>Welkom, <?php echo htmlspecialchars($gebruiker['naam']); ?></h1>
    <section class="container">
        <div class="detail">
            <label for="passagiernummer">Passagiernummer:</label>
            <span><?php echo htmlspecialchars($gebruiker['passagiernummer']); ?></span>
        </div>
        <div class="detail">
            <label for="naam">Naam:</label>
            <span><?php echo htmlspecialchars($gebruiker['naam']); ?></span>
        </div>
        <div class="detail">
            <label for="vluchtnummer">Vluchtnummer:</label>
            <span><?php echo htmlspecialchars($gebruiker['vluchtnummer']); ?></span>
        </div>
        <div class="detail">
            <label for="geslacht">Geslacht:</label>
            <span><?php echo htmlspecialchars($gebruiker['geslacht']); ?></span>
        </div>
        <div class="detail">
            <label for="balienummer">Balienummer:</label>
            <span><?php echo htmlspecialchars($gebruiker['balienummer']); ?></span>
        </div>
        <div class="detail">
            <label for="stoel">Stoel:</label>
            <span><?php echo htmlspecialchars($gebruiker['stoel']); ?></span>
        </div>
    </section>
</body>

</html>
