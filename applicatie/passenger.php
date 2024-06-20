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
    <title>Gebruikersgegevens</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
     <header>
            <h1>Checkin Gelre</h1>
            <nav>
                <ul>
                    <li><a href="uitlog.php">Uitloggen</a></li>
                    <li><a href="sub/vluchten2.php">Vluchten</a></li>
                    <li><a href="sub/baggage2.php">Baggage inchecken</a></li>
    
                </ul>
            </nav>
        </header>

    <h1>Welkom, <?php echo htmlspecialchars($gebruiker['naam']); ?></h1>
    <div class="gegevens">
        <p>Dit zijn uw gegevens:</p>
        <ul>
            <li><strong>Passagiernummer:</strong> <?php echo htmlspecialchars($gebruiker['passagiernummer']); ?></li>
            <li><strong>Naam:</strong> <?php echo htmlspecialchars($gebruiker['naam']); ?></li>
            <li><strong>Vluchtnummer:</strong> <?php echo htmlspecialchars($gebruiker['vluchtnummer']); ?></li>
            <li><strong>Geslacht:</strong> <?php echo htmlspecialchars($gebruiker['geslacht']); ?></li>
            <li><strong>Balienummer:</strong> <?php echo htmlspecialchars($gebruiker['balienummer']); ?></li>
            <li><strong>Stoel:</strong> <?php echo htmlspecialchars($gebruiker['stoel']); ?></li>
        </ul>
    </div>
</body>

</html>
