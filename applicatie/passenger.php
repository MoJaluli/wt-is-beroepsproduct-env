<?php
require_once 'db_connectie.php';
require_once 'sanitize.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['wachtwoord'])) {
    $passengerCode = sanitize($_GET['wachtwoord']);

    $conn = maakVerbinding();

    $sql = "SELECT passagiernummer, naam, vluchtnummer, wachtwoord FROM [GelreAirport].[dbo].[Passagier] WHERE wachtwoord = :wachtwoord";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':wachtwoord', $passengerCode, PDO::PARAM_STR);
    $stmt->execute(); // Execute the query
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if (password_verify($passengerCode, $result['wachtwoord'])) {
            // Redirect to passenger portal
            header("Location: passenger_portal.php?code=" . urlencode($passengerCode));
            exit();
        } else {
            $error = "Invalid passenger code.";
            header("Location: home.php?error=" . urlencode($error));
            exit();
        }
    } else {
        $error = "Invalid passenger code.";
        header("Location: home.php?error=" . urlencode($error));
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkin Gelre - Home</title>
    
    <link rel="stylesheet" href="css/style.css">
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

    <section id="flightDetails">
        <h2>Mijn Vluchtgegevens</h2>
        <p>Vluchtnummer: <span id="passengerFlightNumber"><?php echo $vluchtnummer; ?></span></p>
        <p>Bestemming: <span id="passengerDestination"><?php echo $bestemming; ?></span></p>
        <p>Vertrektijd: <span id="passengerDepartureTime"><?php echo $vertrektijd; ?></span></p>

        <form action="inchecken.php" method="post">
            <label for="baggageWeight">Gewicht van koffer (kg):</label>
            <input type="number" id="baggageWeight" name="baggageWeight" required>

            <label for="numberOfBags">Aantal koffers:</label>
            <input type="number" id="numberOfBags" name="numberOfBags" required>

            <button onclick="window.location.href='inchecken_apart.php';">Inchecken</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2023 Checkin Gelre. Alle rechten voorbehouden.</p>
    </footer>
</body>
</html>
